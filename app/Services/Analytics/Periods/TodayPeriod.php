<?php

namespace App\Services\Analytics\Periods;

use Carbon\Carbon;

class TodayPeriod extends BasePeriod
{
    /**
     * The Period Slug
     */
    public const SLUG = 'today';

    /**
     * The Period Label
     */
    public const LABEL = 'Today';

    /**
     * BUild
     *
     * @return void
     */
    public function build(): void {
        $this->fromDate = Carbon::now()->startOfDay();
        $this->toDate = Carbon::now()->endOfDay();
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
