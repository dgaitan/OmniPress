<?php

namespace App\Services\Analytics;

interface AnalyticServiceable
{
    /**
     * Build the period
     *
     * @param  string  $period
     * @param  string|null  $from
     * @param  string|null  $to
     */
    public function __construct(
        string $period = 'month_to_date',
        string $from = '',
        string $to = ''
    );
}
