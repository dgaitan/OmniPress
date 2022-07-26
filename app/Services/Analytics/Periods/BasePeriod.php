<?php

namespace App\Services\Analytics\Periods;

use Carbon\Carbon;

abstract class BasePeriod implements Periodicable
{
    /**
     * From Date
     *
     * @var Carbon
     */
    protected Carbon $fromDate;

    /**
     * To Date
     *
     * @var Carbon
     */
    protected Carbon $toDate;

    /**
     * The Period Interval to show it as line chart.
     *
     * @var array
     */
    protected array $datePeriodInterval = [];

    /**
     * Get the from date ot this period
     *
     * @return Carbon
     */
    public function getFromDate(): Carbon
    {
        return $this->fromDate;
    }

    /**
     * Get the to date of this period
     *
     * @return Carbon
     */
    public function getToDate(): Carbon
    {
        return $this->toDate;
    }
}
