<?php

namespace App\Services\Analytics\Periods;

use Carbon\Carbon;

class LastWeekPeriod extends BasePeriod
{
    /**
     * The Period Slug
     */
    public const SLUG = 'last_week';

    /**
     * The Period Label
     */
    public const LABEL = 'Last Week';

    /**
     * BUild
     *
     * @return void
     */
    public function build(): void {
        $this->fromDate = Carbon::now()->subWeek()->startOfWeek();
        $this->toDate = Carbon::now()->subWeek()->endOfWeek();
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
