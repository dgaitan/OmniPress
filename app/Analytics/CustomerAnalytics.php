<?php

namespace App\Analytics;

use App\Models\WooCommerce\Customer;
use App\Analytics\Base\Analytics;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class CustomerAnalytics extends Analytics {

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
     * @return integer
     */
    public function getTotalCustomers(): int {
        return $this->getRangeQueryset()->count();
    }

    /**
     * Get The total Orders
     *
     * @return integer
     */
    public function getPeriodTotalCustomers(): int {
        return $this->getPeriodQueryset()->count();
    }

    public function getTotalPercentageDifference() {
        return $this->calculatePercentageComparisson(
            $this->getTotalCustomers(), $this->getPeriodTotalCustomers()
        );
    }

    /**
     * Range Queryset
     *
     * @return Builder
     */
    protected function getRangeQueryset(): Builder {
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
    protected function getPeriodQueryset(): Builder {
        return $this->model::whereBetween(
            'date_created',
            $this->getPeriodDates()
        );
    }
}