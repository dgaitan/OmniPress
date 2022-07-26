<?php

namespace App\Services\Analytics\Periods;

use Carbon\Carbon;

class MonthToDatePeriod extends BasePeriod
{
    /**
     * The Period Slug
     */
    public const SLUG = 'month_to_date';

    /**
     * The Period Label
     */
    public const LABEL = 'Month To Date';

    /**
     * BUild
     *
     * @return void
     */
    public function build(): void {
        $this->fromDate = Carbon::now()->startOfMonth();
        $this->toDate = Carbon::now()->endOfDay();
    }
}
