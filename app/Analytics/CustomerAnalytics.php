<?php

namespace App\Analytics;

use App\Analytics\Base\Analytics;
use App\Models\WooCommerce\Customer;
use Illuminate\Database\Eloquent\Builder;

class CustomerAnalytics extends Analytics
{
    /**
     * Model to analyze
     *
     * @var string
     */
    protected string $model = Customer::class;

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
    public function getTotalCustomers(): int
    {
        return $this->getRangeQueryset()->count();
    }

    /**
     * Get The total Orders
     *
     * @return int
     */
    public function getPeriodTotalCustomers(): int
    {
        return $this->getPeriodQueryset()->count();
    }

    public function getTotalPercentageDifference()
    {
        return $this->calculatePercentageComparisson(
            $this->getTotalCustomers(), $this->getPeriodTotalCustomers()
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
            );
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
        );
    }
}
