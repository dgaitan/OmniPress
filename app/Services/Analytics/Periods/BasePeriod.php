<?php

namespace App\Services\Analytics\Periods;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

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

    public function getPeriodDateIntervalQuery(): CarbonPeriod
    {
        return $this->getFromDate()
            ->daysUntil($this->getToDate());
    }

    /**
     * GEt the date period interval
     *
     * @return Collection
     */
    public function getPeriodDateInterval(): Collection
    {
        if (! $this->datePeriodInterval) {
            $period = $this->getPeriodDateIntervalQuery();

            foreach ($period->toArray() as $date) {
                $this->datePeriodInterval[] = (object) [
                    'format' => $date->format('F j'),
                    'instance' => $date
                ];
            }
        }

        return collect($this->datePeriodInterval);
    }
}
