<?php

namespace App\Services\Analytics;

abstract class BaseAnalyticsService implements AnalyticServiceable
{
    protected Period $period;

    /**
     * Per Page
     *
     * @var integer
     */
    protected int $perPage = 10;

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
    ) {
        $this->period = new Period($period, $from, $to);
    }

    /**
     * Set Per Page value
     *
     * @param integer $perPage
     * @return void
     */
    public function setPerPage(int $perPage): void {
        $this->perPage = $perPage;
    }

    /**
     * Get per page value
     *
     * @return integer
     */
    public function getPerPage(): int {
        return $this->perPage;
    }
}
