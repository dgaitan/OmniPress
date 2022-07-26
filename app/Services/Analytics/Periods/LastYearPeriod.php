<?php

namespace App\Services\Analytics\Periods;

use Carbon\Carbon;

class LastYearPeriod extends BasePeriod
{
    /**
     * The Period Slug
     */
    public const SLUG = 'last_year';

    /**
     * The Period Label
     */
    public const LABEL = 'Last Year';

    /**
     * BUild
     *
     * @return void
     */
    public function build(): void {
        $this->fromDate = Carbon::now()->subYear()->startOfYear();
        $this->toDate = Carbon::now()->subYear()->endOfYear();
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
