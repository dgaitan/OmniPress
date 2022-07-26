<?php

namespace App\Services\Analytics\Periods;

use Carbon\Carbon;

class LastWeekPeriod extends BasePeriod
{
    /**
     * The Period Slug
     */
    public const SLUG = 'last_week';

    /**
     * The Period Label
     */
    public const LABEL = 'Last Week';

    /**
     * BUild
     *
     * @return void
     */
    public function build(): void {
        $this->fromDate = Carbon::now()->subWeek()->startOfWeek();
        $this->toDate = Carbon::now()->subWeek()->endOfWeek();
    }
}
