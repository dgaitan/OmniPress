<?php

namespace App\Services\Analytics\Periods;

use Carbon\Carbon;

class WeekToDatePeriod extends BasePeriod
{
    /**
     * The Period Slug
     */
    public const SLUG = 'week_to_date';

    /**
     * The Period Label
     */
    public const LABEL = 'Week To Date';

    /**
     * BUild
     *
     * @return void
     */
    public function build(): void
    {
        $this->fromDate = Carbon::now()->startOfWeek();
        $this->toDate = Carbon::now()->endOfDay();
    }
}
