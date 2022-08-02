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

    /**
     * Collect Data
     *
     * @return array
     */
    public function getData(): array;

    /**
     * Get analytics content
     *
     * @return array
     */
    public function getContents(): array;

    /**
     * Get Cache Key
     *
     * @return string
     */
    public function getCacheKey(): string;
}
