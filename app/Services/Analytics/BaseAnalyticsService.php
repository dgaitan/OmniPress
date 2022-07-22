<?php

namespace App\Services\Analytics;

abstract class BaseAnalyticsService implements AnalyticServiceable
{
    protected Period $period;

    /**
     * Build the period
     *
     * @param string $period
     * @param string|null $from
     * @param string|null $to
     */
    public function __construct(
        string $period = 'month_to_date',
        string $from = '',
        string $to = ''
    ) {
        $this->period = new Period($period, $from, $to);
    }
}
