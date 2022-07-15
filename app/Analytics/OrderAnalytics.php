<?php

namespace App\Analytics;

use App\Analytics\Base\Analytics;
use App\Models\WooCommerce\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class OrderAnalytics extends Analytics
{
    /**
     * Model to analyze
     *
     * @var string
     */
    protected string $model = Order::class;

    /**
     * Range Queryset
     *
     * @var Builder|null
     */
    protected $rangeQueryset = null;

    /**
     * Period Queryset
     *
     * @var Builder|null
     */
    protected $periodQueryset = null;

    /**
     * Get The total Orders
     *
     * @return int
     */
    public function getTotalOrders(): int
    {
        return $this->getRangeQueryset()->count();
    }

    /**
     * Get total sold
     *
     * @return int
     */
    public function getTotalSold(): int
    {
        return $this->getRangeQueryset()->sum('total');
    }

    /**
     * Get Total Fees
     *
     * @return int
     */
    public function getTotalFees(): int
    {
        return $this->getRangeQueryset()->sum(
            DB::raw('total_tax + shipping_tax + shipping_total')
        );
    }

    /**
     * Get Net Sales Amount of current period
     *
     * @return int
     */
    public function getNetSales(): int
    {
        return $this->getTotalSold() - $this->getTotalFees();
    }

    /**
     * Get The total Orders
     *
     * @return int
     */
    public function getPeriodTotalOrders(): int
    {
        return $this->getPeriodQueryset()->count();
    }

    /**
     * Get total sold
     *
     * @return int
     */
    public function getPeriodTotalSold(): int
    {
        return $this->getPeriodQueryset()->sum('total');
    }

    /**
     * Get Total Fees
     *
     * @return int
     */
    public function getPeriodTotalFees(): int
    {
        return $this->getPeriodQueryset()->sum(
            DB::raw('total_tax + shipping_tax + shipping_total')
        );
    }

    /**
     * Get Net Sales Amount of current period
     *
     * @return int
     */
    public function getPeriodNetSales(): int
    {
        return $this->getPeriodTotalSold() - $this->getPeriodTotalFees();
    }

    public function getSalesPercentageDifference()
    {
        return $this->calculatePercentageComparisson(
            $this->getNetSales(), $this->getPeriodNetSales()
        );
    }

    public function getTotalOrdersPercentageDifference()
    {
        return $this->calculatePercentageComparisson(
            $this->getTotalOrders(), $this->getPeriodTotalOrders()
        );
    }

    /**
     * Range Queryset
     *
     * @return Builder
     */
    protected function getRangeQueryset(): Builder
    {
        if (is_null($this->rangeQueryset)) {
            $this->rangeQueryset = $this->model::whereBetween(
                'date_created',
                $this->getRangeDates()
            )->whereIn('status', ['processing', 'completed']);
        }

        return $this->rangeQueryset;
    }

    /**
     * Get Period Queryset
     *
     * @return Builder
     */
    protected function getPeriodQueryset(): Builder
    {
        return $this->model::whereBetween(
            'date_created',
            $this->getPeriodDates()
        )->whereIn('status', ['processing', 'completed']);
    }
}
