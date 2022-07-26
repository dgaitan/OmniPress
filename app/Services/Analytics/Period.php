<?php

namespace App\Services\Analytics;

use App\Services\Analytics\Periods\CustomPeriod;
use App\Services\Analytics\Periods\LastMonthPeriod;
use App\Services\Analytics\Periods\LastWeekPeriod;
use App\Services\Analytics\Periods\LastYearPeriod;
use App\Services\Analytics\Periods\MonthToDatePeriod;
use App\Services\Analytics\Periods\Periodicable;
use App\Services\Analytics\Periods\TodayPeriod;
use App\Services\Analytics\Periods\WeekToDatePeriod;
use App\Services\Analytics\Periods\YearToDatePeriod;
use App\Services\Analytics\Periods\YesterdayPeriod;
use Carbon\Carbon;
use InvalidArgumentException;

class Period implements AnalyticServiceable
{
    protected $periods = [
        'today' => TodayPeriod::class,
        'yesterday' => YesterdayPeriod::class,
        'week_to_date' => WeekToDatePeriod::class,
        'month_to_date' => MonthToDatePeriod::class,
        'last_week' => LastWeekPeriod::class,
        'last_month' => LastMonthPeriod::class,
        'year_to_date' => YearToDatePeriod::class,
        'last_year' => LastYearPeriod::class,
    ];

    /**
     * Valid Period Keys
     */
    public const VALID_PERIODS = [
        TodayPeriod::SLUG => TodayPeriod::LABEL,
        YesterdayPeriod::SLUG => YesterdayPeriod::LABEL,
        WeekToDatePeriod::SLUG => WeekToDatePeriod::LABEL,
        LastWeekPeriod::SLUG => LastWeekPeriod::LABEL,
        MonthToDatePeriod::SLUG => MonthToDatePeriod::LABEL,
        LastMonthPeriod::SLUG => LastMonthPeriod::LABEL,
        YearToDatePeriod::SLUG => YearToDatePeriod::LABEL,
        LastYearPeriod::SLUG => LastYearPeriod::LABEL,
    ];

    /**
     * Default Period
     *
     * @var string
     */
    protected $defaultPeriod = MonthToDatePeriod::SLUG;

    protected Periodicable $currentPeriod;

    /**
     * Build the period
     *
     * @param  string  $period
     * @param  string|null  $from
     * @param  string|null  $to
     */
    public function __construct(
        protected string $period = MonthToDatePeriod::SLUG,
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
            $this->currentPeriod = new CustomPeriod($this->from, $this->to);

            return;
        }

        $this->currentPeriod = (new $this->periods[$this->period]);
        $this->currentPeriod->build();
    }

    /**
     * Get PEriod Interval
     *
     * @return array
     */
    public function getPeriodInterval(): array
    {
        return [
            $this->getFromDate(),
            $this->getToDate(),
        ];
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
        return $this->currentPeriod->getFromDate();
    }

    /**
     * Get To Date
     *
     * @return Carbon
     */
    public function getToDate(): Carbon
    {
        return $this->currentPeriod->getToDate();
    }

    public function getPeriodDateInterval()
    {
        return $this->currentPeriod->getPeriodDateInterval();
    }

    /**
     * Check if the period slug is valid
     *
     * @param  string  $period
     * @return bool
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
        return array_merge(['custom'], array_keys(self::VALID_PERIODS));
    }
}
