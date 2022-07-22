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

class CauseAnalyticsService extends BaseAnalyticsService
{

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
            ->select('cause_id')
            ->groupBy('cause_id')
            ->orderByRaw('SUM(amount) DESC')
            ->pluck('cause_id')
            ->toArray();

        return Cause::whereIn('id', $causeIds)->get();
    }

    /**
     * Get Causes with donations in Period
     *
     * @return SupportCollection
     */
    public function getCausesWithDonationsInPeriod(): SupportCollection
    {
        $donations = $this->getCausesInPeriod()->map(function ($cause) {
            $donated = $this->getOrderDonationsByPeriodQuery()
                ->whereCauseId($cause->id)
                ->sum('amount');

            return (object) [
                'cause' => $cause,
                'donated' => Money::USD($donated)
            ];
        });

        return $donations;
    }

    public function getCustomersInPeriod()
    {
        $customerIds = $this->getUserDonationsByPeriodQuery()
            ->select('customer_id')
            ->groupBy('customer_id')
            ->orderByRaw('SUM(donation) DESC')
            ->pluck('customer_id')
            ->toArray();

        return Customer::whereIn('id', $customerIds)->get();
    }

    public function getCustomerDonationsInPeriod(): SupportCollection
    {
        $donations = $this->getCustomersInPeriod()->map(function ($customer) {
            $donated = $this->getUserDonationsByPeriodQuery()
                ->whereCustomerId($customer->id)
                ->sum('donation');

            return (object) [
                'customer' => $customer,
                'donated' => Money::USD($donated)
            ];
        });

        return $donations;
    }
}
