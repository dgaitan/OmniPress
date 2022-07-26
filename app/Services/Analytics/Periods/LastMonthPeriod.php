<?php

namespace App\Services\Analytics\Periods;

use Carbon\Carbon;

class LastMonthPeriod extends BasePeriod
{
    /**
     * The Period Slug
     */
    public const SLUG = 'last_month';

    /**
     * The Period Label
     */
    public const LABEL = 'Last Month';

    /**
     * BUild
     *
     * @return void
     */
    public function build(): void
    {
        $this->fromDate = Carbon::now()->subMonth()->startOfMonth();
        $this->toDate = Carbon::now()->subMonth()->endOfMonth();
    }
}
