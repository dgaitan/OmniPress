<?php

namespace App\Analytics;

use App\Models\Membership;
use App\Models\KindCash;
use App\Analytics\Base\Analytics;
use Illuminate\Database\Eloquent\Builder;

class MembershipAnalytics extends Analytics {

    /**
     * Model to analyze
     *
     * @var string
     */
    protected string $model = Membership::class;

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
    public function getTotalMemberships(): int {
        return $this->getRangeQueryset()->count();
    }

    /**
     * Get The total Orders
     *
     * @return integer
     */
    public function getPeriodTotalMemberships(): int {
        return $this->getPeriodQueryset()->count();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getTotalPercentageDifference() {
        return $this->calculatePercentageComparisson(
            $this->getTotalMemberships(), $this->getPeriodTotalMemberships()
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
                'start_at',
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
            'start_at',
            $this->getPeriodDates()
        );
    }
}
