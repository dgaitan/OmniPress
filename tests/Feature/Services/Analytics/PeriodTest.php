<?php

namespace Tests\Feature\Services\Analytics;

use App\Services\Analytics\Period;
use Carbon\Carbon;
use Tests\TestCase;

class PeriodTest extends TestCase
{
    public function test_period_class_should_returns_a_list_of_valid_period_keys(): void
    {
        $periods = array_keys([
            'today' => 'Today',
            'yesterday' => 'Yesterday',
            'week_to_date' => 'Week to Date',
            'last_week' => 'Last Week',
            'month_to_date' => 'Month to Date',
            'last_month' => 'Last Month',
            'year_to_date' => 'Year to Date',
            'last_year' => 'Last Year'
        ]);

        $periods = array_merge(['custom'], $periods);

        $this->assertEquals($periods, Period::getValidPeriodsKeys());
    }

    public function test_period_is_valid_static_method_should_validate_right_periods(): void
    {
        $this->assertTrue(Period::isValidPeriod('today'));
        $this->assertTrue(Period::isValidPeriod('yesterday'));
        $this->assertTrue(Period::isValidPeriod('week_to_date'));
        $this->assertTrue(Period::isValidPeriod('last_week'));
        $this->assertTrue(Period::isValidPeriod('month_to_date'));
        $this->assertTrue(Period::isValidPeriod('last_month'));
        $this->assertTrue(Period::isValidPeriod('year_to_date'));
        $this->assertTrue(Period::isValidPeriod('last_year'));
        $this->assertTrue(Period::isValidPeriod('custom'));
        $this->assertFalse(Period::isValidPeriod('invalid_period'));
    }

    public function test_period_should_work_with_current_month_by_default(): void
    {
        $period = new Period();

        $this->assertEquals('month_to_date', $period->getPeriod());

        // Assert that dates must be instance of carbon
        $this->assertInstanceOf(Carbon::class, $period->getFromDate());
        $this->assertInstanceOf(Carbon::class, $period->getToDate());

        $fromDate = Carbon::now()->startOfMonth();
        $toDate = Carbon::now()->endOfDay();

        $this->assertEquals($period->getFromDate(), $fromDate);
        $this->assertEquals($period->getToDate(), $toDate);
    }

    public function test_it_should_accepts_today_period_and_get_right_values(): void
    {
        $period = new Period('today');

        $this->assertEquals('today', $period->getPeriod());

        // Assert that dates must be instance of carbon
        $this->assertInstanceOf(Carbon::class, $period->getFromDate());
        $this->assertInstanceOf(Carbon::class, $period->getToDate());

        $this->assertEquals(
            $period->getFromDate(),
            Carbon::now()->startOfDay()
        );
        $this->assertEquals(
            $period->getToDate(),
            Carbon::now()->endOfDay()
        );

        $this->assertEquals([
            Carbon::now()->startOfDay(),
            Carbon::now()->endOfDay()
        ], $period->getPeriodInterval());
    }

    public function test_it_should_accepts_yesterday_period_and_get_right_values(): void
    {
        $period = new Period('yesterday');

        $this->assertEquals('yesterday', $period->getPeriod());

        // Assert that dates must be instance of carbon
        $this->assertInstanceOf(Carbon::class, $period->getFromDate());
        $this->assertInstanceOf(Carbon::class, $period->getToDate());

        $this->assertEquals(
            $period->getFromDate(),
            Carbon::now()->subDay()->startOfDay()
        );
        $this->assertEquals(
            $period->getToDate(),
            Carbon::now()->subDay()->endOfDay()
        );

        $this->assertEquals([
            Carbon::now()->subDay()->startOfDay(),
            Carbon::now()->subDay()->endOfDay()
        ], $period->getPeriodInterval());
    }

    public function test_it_should_accepts_week_to_date_and_get_right_values(): void
    {
        $period = new Period('week_to_date');

        $this->assertEquals('week_to_date', $period->getPeriod());

        // Assert that dates must be instance of carbon
        $this->assertInstanceOf(Carbon::class, $period->getFromDate());
        $this->assertInstanceOf(Carbon::class, $period->getToDate());

        $this->assertEquals(
            $period->getFromDate(),
            Carbon::now()->subWeek()->startOfWeek()
        );
        $this->assertEquals(
            $period->getToDate(),
            Carbon::now()->endOfDay()
        );

        $this->assertEquals([
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->endOfDay()
        ], $period->getPeriodInterval());
    }

    public function test_it_should_accepts_last_week_and_get_right_values(): void
    {
        $period = new Period('last_week');

        $this->assertEquals('last_week', $period->getPeriod());

        // Assert that dates must be instance of carbon
        $this->assertInstanceOf(Carbon::class, $period->getFromDate());
        $this->assertInstanceOf(Carbon::class, $period->getToDate());

        $this->assertEquals(
            $period->getFromDate(),
            Carbon::now()->subWeek()->startOfWeek()
        );
        $this->assertEquals(
            $period->getToDate(),
            Carbon::now()->subWeek()->endOfWeek()
        );

        $this->assertEquals([
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek()
        ], $period->getPeriodInterval());
    }

    public function test_it_should_accepts_last_month_and_get_right_values(): void
    {
        $period = new Period('last_month');

        $this->assertEquals('last_month', $period->getPeriod());

        // Assert that dates must be instance of carbon
        $this->assertInstanceOf(Carbon::class, $period->getFromDate());
        $this->assertInstanceOf(Carbon::class, $period->getToDate());

        $this->assertEquals(
            $period->getFromDate(),
            Carbon::now()->subMonth()->startOfMonth()
        );
        $this->assertEquals(
            $period->getToDate(),
            Carbon::now()->subMonth()->endOfMonth()
        );

        $this->assertEquals([
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonth()->endOfMonth()
        ], $period->getPeriodInterval());
    }

    public function test_it_should_accepts_year_to_date_and_get_right_values(): void
    {
        $period = new Period('year_to_date');

        $this->assertEquals('year_to_date', $period->getPeriod());

        // Assert that dates must be instance of carbon
        $this->assertInstanceOf(Carbon::class, $period->getFromDate());
        $this->assertInstanceOf(Carbon::class, $period->getToDate());

        $this->assertEquals(
            $period->getFromDate(),
            Carbon::now()->startOfYear()
        );
        $this->assertEquals(
            $period->getToDate(),
            Carbon::now()->endOfDay()
        );

        $this->assertEquals([
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfDay()
        ], $period->getPeriodInterval());
    }

    public function test_it_should_accepts_last_year_and_get_right_values(): void
    {
        $period = new Period('last_year');

        $this->assertEquals('last_year', $period->getPeriod());

        // Assert that dates must be instance of carbon
        $this->assertInstanceOf(Carbon::class, $period->getFromDate());
        $this->assertInstanceOf(Carbon::class, $period->getToDate());

        $this->assertEquals(
            $period->getFromDate(),
            Carbon::now()->subYear()->startOfYear()
        );
        $this->assertEquals(
            $period->getToDate(),
            Carbon::now()->subYear()->endOfYear()
        );

        $this->assertEquals([
            Carbon::now()->subYear()->startOfYear(),
            Carbon::now()->subYear()->endOfYear()
        ], $period->getPeriodInterval());
    }

    public function test_it_should_accepts_custom_periods_range_by_passing_a_string_date(): void
    {
        $start = '2022-02-01';
        $end = '2022-05-31';

        $period = new Period('custom', $start, $end);

        $this->assertEquals('custom', $period->getPeriod());

        // Assert that dates must be instance of carbon
        $this->assertInstanceOf(Carbon::class, $period->getFromDate());
        $this->assertInstanceOf(Carbon::class, $period->getToDate());

        $this->assertEquals(
            $period->getFromDate(),
            Carbon::parse($start)
        );
        $this->assertEquals(
            $period->getToDate(),
            Carbon::parse($end)
        );

        $this->assertEquals([
            Carbon::parse($start),
            Carbon::parse($end)
        ], $period->getPeriodInterval());
    }
}
