<?php

namespace App\Services\Analytics;

use App\Models\Causes\Cause;
use App\Models\Causes\OrderDonation;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class CauseAnalyticsService extends BaseAnalyticsService implements AnalyticServiceable
{
    public const CACHE_TAG = 'cause_stats';

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
                    'color' => '#'.substr(str_shuffle('ABCDEF0123456789'), 0, 6),
                ];
            })->toArray(),
        ];
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
     * @param  Collection  $donations
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
}
