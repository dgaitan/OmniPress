<?php

namespace App\Services\Analytics;

use App\Models\WooCommerce\Order;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use stdClass;

class OrderAnalyticsService extends BaseAnalyticsService implements AnalyticServiceable
{
    public const CACHE_TAG = 'order_stats';

    /**
     * Get contents without cache
     *
     * @return array
     */
    public function getContents(): array
    {
        return [
            'total' => $this->getTotalInPeriod(),
            'net_sales' => $this->getNetSalesInPeriod(),
            'total_orders' => $this->getTotalOrdersInPeriod(),
            'order_totals_by_interval' => $this->getOrdersTotalsByPeriodInterval(),
            'order_count_by_interval' => $this->getOrderCountByPeriodInterval()
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

        return $data;
    }

    /**
     * INitialize the query to retrieve the doantion in a period of time.
     *
     * @return Builder
     */
    protected function getOrdersInPeriodQuery(): Builder
    {
        return Order::whereBetween(
            'date_created', $this->period->getPeriodInterval()
        );
    }

    /**
     * Get the total donated in the current Period
     *
     * @return Money
     */
    public function getTotalInPeriod(bool $asMoney = true): Money|int|null
    {
        $total = $this->getOrExecuteQuery(
            queryName: 'totalInPeriod',
            callbackQuery: function () {
                return $this->getOrdersInPeriodQuery()
                    ->sum('total');
            }
        );

        return $asMoney ? Money::USD($total) : $total;
    }

    /**
     * Get the total donated in the current Period
     *
     * @return Money
     */
    public function getFeeTotalsInPeriod(bool $asMoney = true): Money|int|null
    {
        $total = $this->getOrExecuteQuery(
            queryName: 'feeTotalsInPeriod',
            callbackQuery: function () {
                return $this->getOrdersInPeriodQuery()
                    ->sum(DB::raw('total_tax + shipping_tax + shipping_total'));
            }
        );

        return $asMoney ? Money::USD($total) : $total;
    }

    /**
     * Get the total donated in the current Period
     *
     * @return Money
     */
    public function getNetSalesInPeriod(bool $asMoney = true): Money|int|null
    {
        $total = $this->getTotalInPeriod(asMoney: false);
        $totalFees = $this->getFeeTotalsInPeriod(asMoney: false);
        $netSales = $total - $totalFees;

        return $asMoney ? Money::USD($netSales) : $netSales;
    }

    /**
     * Get total order in the current period
     *
     * @return integer
     */
    public function getTotalOrdersInPeriod(): int
    {
        return $this->getOrExecuteQuery(
            queryName: 'totalOrdersInPeriod',
            callbackQuery: fn() => $this->getOrdersInPeriodQuery()->count()
        );
    }

    /**
     * Get Orders In the current Period
     *
     * @return Collection
     */
    public function getOrdersInPeriod(): Collection
    {
        return $this->getOrExecuteQuery(
            queryName: 'ordersInPeriod',
            callbackQuery: fn() => $this->getOrdersInPeriodQuery()->get()
        );
    }

    /**
     * Get Order Totals by Period Interval.
     *
     * What it means?
     *
     * Well, we need to print the totals per day or per month in a simple graph.
     * So we are going to need to collect the data per each item where each item
     * is the interval in the current period. (days or month).
     *
     * Ie: It should returns the following structure for the period Aug 5 to Aug 10
     *
     * [
     *  ['label' => 'Aug 5', 'total' => '$10'],
     *  ['label' => 'Aug 6, 'total' => '$20'],
     *  ['label' => 'Aug 7', 'total' => '$20'],
     *  ['label' => 'Aug 8', 'total' => '$21'],
     *  ['label' => 'Aug 9', 'total' => '$40'],
     *  ['label' => 'Aug 10', 'total' => '$1']
     * ]
     *
     * Important to know that the method `getPeriodDateInterval()` returns the period range
     * in days or month in depends of the kind of period.
     *
     * @TODO: This interval is hardcoded on each period class. But we need to add
     * the ability to define how the users want to see the intervals. IF daily,
     * weekly or monthly.
     *
     * @return array
     */
    public function getOrdersTotalsByPeriodInterval(): array
    {
        /**
         * Map the days/months intervals between period dates. See the `getPeriodDateInterval` in each
         * Period Class or in the BasePeriod class to know if it comes as daily or monthly.
         *
         * @TODO: We need to let users define it from frontend in the future.
         *
         * `getPeriodDateInterval` method returns an array of days or months.
         */
        return $this->period->getPeriodDateInterval()->map(function ($interval) {
            // Filter by orders that were created in the same date of the current day or month.
            $taxTotals = $this->filterItemsForPeriodInterval($interval)
                ->sum('total_tax');
            $shippingTotals = $this->filterItemsForPeriodInterval($interval)
                ->sum('shipping_total');
            $totalBase = $this->filterItemsForPeriodInterval($interval)
                ->sum('total');
            $netSale = $totalBase - ( $taxTotals + $shippingTotals );

            return [
                'label' => $interval->format,
                'total' => Money::USD($netSale)
            ];
        })->toArray();
    }

    /**
     * Get Total Orders by Period Interval.
     *
     * What it means?
     *
     * Well, we need to print the totals per day or per month in a simple graph.
     * So we are going to need to collect the data per each item where each item
     * is the interval in the current period. (days or month).
     *
     * Ie: It should returns the following structure for the period Aug 5 to Aug 10
     *
     * [
     *  ['label' => 'Aug 5', 'total' => 4],
     *  ['label' => 'Aug 6, 'total' => 10],
     *  ['label' => 'Aug 7', 'total' => 5],
     *  ['label' => 'Aug 8', 'total' => 1],
     *  ['label' => 'Aug 9', 'total' => 0],
     *  ['label' => 'Aug 10', 'total' => 12]
     * ]
     *
     * Important to know that the method `getPeriodDateInterval()` returns the period range
     * in days or month in depends of the kind of period.
     *
     * @TODO: This interval is hardcoded on each period class. But we need to add
     * the ability to define how the users want to see the intervals. IF daily,
     * weekly or monthly.
     *
     * @return array
     */
    public function getOrderCountByPeriodInterval(): array
    {
        return $this->period->getPeriodDateInterval()->map(function ($interval) {
            $total = $this->filterItemsForPeriodInterval($interval)->count();

            return [
                'label' => $interval->format,
                'total' => $total
            ];
        })->toArray();
    }

    /**
     * Quick helper to filter the items in a collection that
     * are in the same period interval
     *
     * @param stdClass $periodInterval
     * @return SupportCollection
     */
    protected function filterItemsForPeriodInterval(stdClass $periodInterval): SupportCollection
    {
        return $this->getOrdersInPeriod()->filter(function ($order) use ($periodInterval) {
            return $this->period->isSame(date: $order->date_created, with: $periodInterval->instance);
        });
    }
}
