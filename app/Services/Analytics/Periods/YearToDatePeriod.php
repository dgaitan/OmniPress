<?php

namespace App\Services\Analytics\Periods;

use Carbon\Carbon;

class YearToDatePeriod extends BasePeriod
{
    /**
     * The Period Slug
     */
    public const SLUG = 'year_to_date';

    /**
     * The Period Label
     */
    public const LABEL = 'Year To Date';

    /**
     * BUild
     *
     * @return void
     */
    public function build(): void {
        $this->fromDate = Carbon::now()->startOfYear();
        $this->toDate = Carbon::now()->endOfDay();
    }
}
