<?php

namespace App\Services\Analytics\Periods;

use Carbon\Carbon;

class MonthToDatePeriod implements Periodicable {
    /**
     * The Period Slug
     */
    public const SLUG = 'month_to_date';

    /**
     * The Period Label
     */
    public const LABEL = 'Month To Date';

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
     * BUild
     *
     * @return void
     */
    public function build(): void {
        $this->fromDate = Carbon::now()->startOfMonth();
        $this->toDate = Carbon::now()->endOfDay();
    }

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

    /**
     * GEt the date period interval
     *
     * @return array
     */
    public function getDatePeriodInterval(): array
    {
        if (! $this->datePeriodInterval) {
            $period = $this->getFromDate()
                ->daysUntil($this->getToDate());

            foreach ($period as $date) {
                $this->datePeriodInterval[$date] = [
                    'format' => $date->format('j, Y'),
                    'instance' => $date
                ];
            }
        }

        return $this->datePeriodInterval;
    }
}
