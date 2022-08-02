<?php

namespace App\Services\Analytics;

use App\Models\Causes\UserDonation;
use App\Models\WooCommerce\Customer;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class UserImpactAnalyticsService extends BaseAnalyticsService implements AnalyticServiceable
{
    public const CACHE_TAG = 'user_impact_stats';

    /**
     * Get contents without cache
     *
     * @return array
     */
    public function getContents(): array
    {
        return [
            'totalDonated' => $this->getTotalDonatedInPeriod(),
            'userImpacts' => $this->getCustomerDonationsInPeriod(),
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
        $data = parent::getSerializedData(cached: $cached, perPage: $perPage);

        return [
            'totalDonated' => $data['totalDonated'],
            'userImpacts' => $data['userImpacts']->map(function ($item) {
                return [
                    'customer' => [
                        'id' => $item->customer->id,
                        'customer_id' => $item->customer->customer_id,
                        'first_name' => $item->customer->first_name,
                        'email' => $item->customer->email,
                        'last_name' => $item->customer->last_name,
                        'avatar' => $item->customer->avatar_url,
                    ],
                    'donated' => $item->donated,
                    'intervals' => $item->intervals,
                    'color' => '#'.substr(str_shuffle('ABCDEF0123456789'), 0, 6),
                ];
            })->toArray(),
        ];
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
        $totalDonated = $this->getUserDonationsByPeriodQuery()
            ->sum('donation');

        return Money::USD($totalDonated);
    }

    /**
     * Get Cause Donations By Each Period
     *
     * @param  Collection  $donations
     * @return array
     */
    public function getCauseDonationsByPeriodInterval(Collection $donations): array
    {
        return $this->period->getPeriodDateInterval()->map(function ($interval) use ($donations) {
            $donation = $donations->filter(function ($value, $key) use ($interval) {
                return $this->period->isSame(date: $value->donation_date, with: $interval->instance);
            })->sum('donation');

            return (object) [
                'label' => $interval->format,
                'amount' => Money::USD($donation),
            ];
        })->toArray();
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
     * @param  Collection  $donations
     * @return array
     */
    public function getUserDonationsByPeriodInterval(Collection $donations): array
    {
        return $this->period->getPeriodDateInterval()->map(function ($interval) use ($donations) {
            $donation = $donations->filter(function ($value, $key) use ($interval) {
                return $this->period->isSame(date: $value->donation_date, with: $interval->instance);
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
                'intervals' => $this->getUserDonationsByPeriodInterval($donations),
            ];
        });

        return $donations;
    }
}
