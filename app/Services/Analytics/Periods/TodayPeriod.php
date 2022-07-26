<?php

namespace App\Services\Analytics\Periods;

use Carbon\Carbon;

class TodayPeriod extends BasePeriod
{
    /**
     * The Period Slug
     */
    public const SLUG = 'today';

    /**
     * The Period Label
     */
    public const LABEL = 'Today';

    /**
     * BUild
     *
     * @return void
     */
    public function build(): void {
        $this->fromDate = Carbon::now()->startOfDay();
        $this->toDate = Carbon::now()->endOfDay();
    }
}
