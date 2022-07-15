<?php

namespace App\Analytics\Base;

use Carbon\Carbon;

abstract class Analytics
{
    /**
     * Comparisson Ranges
     */
    const CURRENT_MONTH_RANGE = 'current_month';

    const LAST_MONTH_RAGE = 'last_month';

    const CURRENT_YEAR_RANGE = 'current_year';

    /**
     * Comparisson Periods
     */
    const PREVIOUS_PERIOD = 'previous_period';

    const PREVIOUS_YEAR = 'previous_year';

    /**
     * Comparisson group
     *
     * @var string
     */
    protected string $rangeGroup = 'month';

    /**
     * Comparisson group
     *
     * @var string
     */
    protected string $periodGroup = 'month';

    /**
     * Comparisson Range Value
     *
     * @var string
     */
    protected string $comparissonRange = '';

    /**
     * Comparisson Period Value
     *
     * @var string
     */
    protected string $comparissonPeriod = '';

    /**
     * Undocumented function
     */
    public function __construct(string $range, string $period)
    {
        $this->setComparisonRange($range);
        $this->comparissonPeriod = $period;
    }

    /**
     * Get Date Ranges
     *
     * @return array
     */
    protected function getRangeDates(): array
    {
        $ranges = [
            self::CURRENT_MONTH_RANGE => [
                (new Carbon)->startOfMonth(), Carbon::now(),
            ],
            self::LAST_MONTH_RAGE => [
                (new Carbon)->subMonth(1)->startOfMonth(),
                (new Carbon)->subMonth(1)->endOfMonth(),
            ],
            self::CURRENT_YEAR_RANGE => [
                (new Carbon)->startOfYear(),
                (new Carbon)->endOfYear(),
            ],
        ];

        return $ranges[$this->comparissonRange];
    }

    /**
     * Get Period Dates
     *
     * @return array
     */
    protected function getPeriodDates(): array
    {
        $dateRanges = $this->getRangeDates();

        $periods = [
            self::PREVIOUS_PERIOD => [
                Carbon::create($dateRanges[0])
                    ->{$this->getPeriodSubstractionMethod('sub')}(1)
                    ->{$this->getPeriodSubstractionMethod('startOf')}(),
                Carbon::create($dateRanges[0])
                    ->{$this->getPeriodSubstractionMethod('sub')}(1)
                    ->{$this->getPeriodSubstractionMethod('endOf')}(),
            ],
            self::PREVIOUS_YEAR => [
                Carbon::create($dateRanges[0])->subYear()->startOfYear(),
                Carbon::create($dateRanges[0])->subYear()->endOfYear(),
            ],
        ];

        return $periods[$this->comparissonPeriod];
    }

    /**
     * Get the substraction method name to define or load
     * the previous perdio
     *
     * @return string
     */
    protected function getPeriodSubstractionMethod(string $prefix): string
    {
        return sprintf(
            '%s%s',
            $prefix,
            ucfirst($this->rangeGroup)
        );
    }

    /**
     * Set Comparisson Range
     *
     * @param  string  $range
     * @return void
     */
    protected function setComparisonRange(string $range): void
    {
        $readRange = explode('_', $range);

        $this->rangeGroup = end($readRange);
        $this->comparissonRange = $range;
    }

    /**
     * Te formula to calculate percentage of comparission
     * between to values is the following one:
     *
     * @param  int  $baseValue
     * @param  int  $actualValue
     * @return mixed
     */
    protected function calculatePercentageComparisson(int $actualValue, int $baseValue): mixed
    {
        if ($baseValue === 0 && $actualValue === 0) {
            return 0;
        }

        return (($actualValue - $baseValue) / $baseValue) * 100;
    }
}
