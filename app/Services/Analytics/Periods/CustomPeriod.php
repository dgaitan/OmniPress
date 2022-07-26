<?php

namespace App\Services\Analytics\Periods;

use Carbon\Carbon;

class CustomPeriod extends BasePeriod
{
    /**
     * The Period Slug
     */
    public const SLUG = 'custom';

    /**
     * The Period Label
     */
    public const LABEL = 'Custom';

    public function __construct(string $from, string $to) {
        $this->fromDate = Carbon::parse($from);
        $this->toDate = Carbon::parse($to);
    }

    /**
     * BUild
     *
     * @return void
     */
    public function build(): void {}

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
