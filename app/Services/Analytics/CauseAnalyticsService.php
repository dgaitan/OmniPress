<?php

namespace App\Services\Analytics;

use App\Models\Causes\Cause;
use App\Models\Causes\OrderDonation;
use App\Models\Causes\UserDonation;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Order;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Cache;

class CauseAnalyticsService extends BaseAnalyticsService
{
    public const CACHE_TAG = 'cause_stats';

    /**
     * Collect Data
     *
     * @return array
     */
    public function getData(): array
    {
        if ($this->period->getPeriod() === 'month_to_date') {
            return $this->getContents();
        }

        if (! Cache::tags(self::CACHE_TAG)->has($this->getCacheKey())) {
            Cache::tags(self::CACHE_TAG)->remember($this->getCacheKey(), now()->addYear(1), function () {
                return $this->getContents();
            });
        }

        return Cache::tags(self::CACHE_TAG)->get($this->getCacheKey());
    }

    /**
     * Get contents without cache
     *
     * @return array
     */
    public function getContents(): array
    {
        return [
            'totalDonated' => $this->getTotalDonatedInPeriod(),
            'causeDonations' => $this->getCausesWithDonationsInPeriod(),
            'customerDonations' => $this->getCustomerDonationsInPeriod(),
        ];
    }

    /**
     * Get data serialised just if it's needed for views
     *
     * @param  bool  $cached
     * @return array
     */
    public function getSerializedData(bool $cached = false, int|null $perPage = null): array
    {
        if ($perPage) {
            $this->setPerPage($perPage);
        }

        $data = $cached ? $this->getData() : $this->getContents();

        return [
            'totalDonated' => $data['totalDonated'],
            'causeDonations' => $data['causeDonations']->map(function ($item) {
                return [
                    'cause' => [
                        'id' => $item->cause->id,
                        'name' => $item->cause->name,
                        'cause_type' => $item->cause->cause_type,
                        'cause_type_label' => $item->cause->getCauseType(),
                        'image_url' => $item->cause->getImage(),
                        'beneficiary' => $item->cause->beneficiary,
                    ],
                    'donated' => $item->donated,
                    'intervals' => $item->intervals,
                    'color' => '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6)
                ];
            })->toArray(),
            'customerDonations' => $data['customerDonations']->map(function ($item) {
                return [
                    'customer' => [
                        'id' => $item->customer->id,
                        'customer_id' => $item->customer->customer_id,
                        'first_name' => $item->customer->first_name,
                        'last_name' => $item->customer->last_name,
                        'avatar' => $item->customer->avatar_url,
                    ],
                    'donated' => $item->donated,
                    'intervals' => $item->intervals,
                    'color' => '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6)
                ];
            })->toArray(),
        ];
    }

    /**
     * Get cache string
     *
     * @return string
     */
    public function getCacheKey(): string
    {
        return sprintf(
            'cause_stats_from_%s_to_%s_%s',
            $this->period->getFromDate()->format('Y_m_d_h_i_s'),
            $this->period->getToDate()->format('Y_m_d_h_i_s'),
            $this->getPerPage()
        );
    }

    public function getOrdersPerPeriod()
    {
        return Order::whereBetween(
            'date_created', $this->period->getPeriodInterval()
        )->get();
    }

    /**
     * INitialize the query to retrieve the doantion in a period of time.
     *
     * @return Builder
     */
    protected function getOrderDonationsByPeriodQuery(): Builder
    {
        return OrderDonation::whereBetween(
            'donation_date', $this->period->getPeriodInterval()
        );
    }

    /**
     * Get User Donations in Period
     *
     * @return Builder
     */
    protected function getUserDonationsByPeriodQuery(): Builder
    {
        return UserDonation::whereBetween(
            'donation_date', $this->period->getPeriodInterval()
        );
    }

    /**
     * Get the total donated in the current Period
     *
     * @return Money
     */
    public function getTotalDonatedInPeriod(): Money
    {
        $totalDonated = $this->getOrderDonationsByPeriodQuery()
            ->sum('amount');

        return Money::USD($totalDonated);
    }

    /**
     * Get Causes used in the current period
     *
     * @return Collection
     */
    public function getCausesInPeriod(): Collection
    {
        $causeIds = $this->getOrderDonationsByPeriodQuery()
            ->take($this->getPerPage())
            ->select('cause_id')
            ->groupBy('cause_id')
            ->orderByRaw('SUM(amount) DESC')
            ->pluck('cause_id')
            ->toArray();

        return Cause::whereIn('id', $causeIds)->get();
    }

    /**
     * Get Cause Donations By Each Period
     *
     * @param Collection $donations
     * @return array
     */
    public function getCauseDonationsByPeriodInterval(Collection $donations): array
    {
        return $this->period->getPeriodDateInterval()->map(function ($interval) use ($donations) {
            $donation = $donations->filter(function ($value, $key) use ($interval) {
                return $this->period->isSame(date: $value->donation_date, with: $interval->instance);
            })->sum('amount');

            return (object) [
                'label' => $interval->format,
                'amount' => Money::USD($donation),
            ];
        })->toArray();
    }

    /**
     * Get Causes with donations in Period
     *
     * @return SupportCollection
     */
    public function getCausesWithDonationsInPeriod(): SupportCollection
    {
        $donations = $this->getCausesInPeriod()->map(function ($cause) {
            $_donations = $this->getOrderDonationsByPeriodQuery()
                ->whereCauseId($cause->id)
                ->get();
            $donated = $_donations->sum('amount');

            return (object) [
                'cause' => $cause,
                'donated' => Money::USD($donated),
                'intervals' => $this->getCauseDonationsByPeriodInterval($_donations),
            ];
        });

        return $donations;
    }

    /**
     * Get Customers in Period
     *
     * @return Collection
     */
    public function getCustomersInPeriod(): Collection
    {
        $customerIds = $this->getUserDonationsByPeriodQuery()
            ->take($this->getPerPage())
            ->select('customer_id')
            ->groupBy('customer_id')
            ->orderByRaw('SUM(donation) DESC')
            ->pluck('customer_id')
            ->toArray();

        return Customer::whereIn('id', $customerIds)->get();
    }

    /**
     * Get Cause Donations By Each Period
     *
     * @param Collection $donations
     * @return array
     */
    public function getUserDonationsByPeriodInterval(Collection $donations): array
    {
        return $this->period->getPeriodDateInterval()->map(function ($interval) use ($donations) {
            $donation = $donations->filter(function ($value, $key) use ($interval) {
                return in_array($this->period->getPeriod(), ['year_to_date'])
                    ? $value->donation_date->isSameMonth($interval->instance)
                    : $value->donation_date->isSameDay($interval->instance);
            })->sum('donation');

            return (object) [
                'label' => $interval->format,
                'amount' => Money::USD($donation),
            ];
        })->toArray();
    }

    /**
     * Get Customer Donations in Period
     *
     * @return SupportCollection
     */
    public function getCustomerDonationsInPeriod(): SupportCollection
    {
        $donations = $this->getCustomersInPeriod()->map(function ($customer) {
            $donations = $this->getUserDonationsByPeriodQuery()
                ->whereCustomerId($customer->id)
                ->get();
            $donated = $donations->sum('donation');

            return (object) [
                'customer' => $customer,
                'donated' => Money::USD($donated),
                'intervals' => $this->getUserDonationsByPeriodInterval($donations)
            ];
        });

        return $donations;
    }

    /**
     * Forget cache of current instance
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::tags(self::CACHE_TAG)->forget($this->getCacheKey());
    }

    /**
     * Clear Cache Data
     *
     * @return void
     */
    public static function clearCacheData(): void
    {
        Cache::tags(self::CACHE_TAG)->flush();
    }
}
