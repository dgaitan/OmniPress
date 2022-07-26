<?php

namespace App\Services\Analytics\Periods;

use Carbon\Carbon;

class YearToDatePeriod extends BasePeriod
{
    /**
     * The Period Slug
     */
    public const SLUG = 'year_to_date';

    /**
     * The Period Label
     */
    public const LABEL = 'Year To Date';

    /**
     * BUild
     *
     * @return void
     */
    public function build(): void {
        $this->fromDate = Carbon::now()->startOfYear();
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
