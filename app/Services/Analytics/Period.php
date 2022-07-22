<?php

namespace App\Services\Analytics;

use Carbon\Carbon;
use InvalidArgumentException;

class Period implements AnalyticServiceable
{
    /**
     * Valid Period Keys
     */
    public const VALID_PERIODS = [
        'today' => 'Today',
        'yesterday' => 'Yesterday',
        'week_to_date' => 'Week to Date',
        'last_week' => 'Last Week',
        'month_to_date' => 'Month to Date',
        'last_month' => 'Last Month',
        'year_to_date' => 'Year to Date',
        'last_year' => 'Last Year'
    ];

    /**
     * Default Period
     *
     * @var string
     */
    protected $defaultPeriod = 'month_to_date';

    protected Carbon $fromDate;
    protected Carbon $toDate;

    /**
     * Build the period
     *
     * @param string $period
     * @param string|null $from
     * @param string|null $to
     */
    public function __construct(
        protected string $period = 'month_to_date',
        protected string $from = '',
        protected string $to = ''
    ) {
        if (! self::isValidPeriod($this->period)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid Period Slug. Be sure of using: %s',
                    implode(', ', self::getValidPeriodsKeys())
                )
            );
        }

        $this->buildPeriod();
    }

    /**
     * Build Period
     *
     * @return void
     */
    protected function buildPeriod(): void
    {
        if ($this->period === 'custom') {
            $this->fromDate = Carbon::parse($this->from);
            $this->toDate = Carbon::parse($this->to);

            return;
        }

        $datePeriods = $this->getPeriodDateInvertals();
        $this->fromDate = $datePeriods[0];
        $this->toDate = $datePeriods[1];
    }

    /**
     * Get PEriod Interval
     *
     * @return array
     */
    public function getPeriodInterval(): array
    {
        return [$this->fromDate, $this->toDate];
    }

    /**
     * Get Period Interval
     *
     * @return array
     */
    public function getPeriodDateInvertals(): array
    {
        return [
            'today' => [
                Carbon::now()->startOfDay(),
                Carbon::now()->endOfDay()
            ],
            'yesterday' => [
                Carbon::now()->subDay()->startOfDay(),
                Carbon::now()->subDay()->endOfDay()
            ],
            'week_to_date' => [
                Carbon::now()->subWeek()->startOfWeek(),
                Carbon::now()->endOfDay()
            ],
            'last_week' => [
                Carbon::now()->subWeek()->startOfWeek(),
                Carbon::now()->subWeek()->endOfWeek()
            ],
            'month_to_date' => [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfDay()
            ],
            'last_month' => [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth()
            ],
            'year_to_date' => [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfDay()
            ],
            'last_year' => [
                Carbon::now()->subYear()->startOfYear(),
                Carbon::now()->subYear()->endOfYear()
            ]
        ][$this->period];
    }

    /**
     * Retrieve the current period
     *
     * @return string
     */
    public function getPeriod(): string
    {
        return $this->period;
    }

    /**
     * Get From Date
     *
     * @return Carbon
     */
    public function getFromDate(): Carbon
    {
        return $this->fromDate;
    }

    /**
     * Get To Date
     *
     * @return Carbon
     */
    public function getToDate(): Carbon
    {
        return $this->toDate;
    }

    /**
     * Check if the period slug is valid
     *
     * @param string $period
     * @return boolean
     */
    public static function isValidPeriod(string $period): bool
    {
        return in_array($period, self::getValidPeriodsKeys());
    }

    /**
     * Get Valid Periods Keys
     *
     * @return array
     */
    public static function getValidPeriodsKeys(): array
    {
        return array_merge( array('custom') , array_keys(self::VALID_PERIODS));
    }
}
