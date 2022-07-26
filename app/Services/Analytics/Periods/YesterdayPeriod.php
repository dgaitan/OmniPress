<?php

namespace App\Services\Analytics\Periods;

use Carbon\Carbon;

class YesterdayPeriod extends BasePeriod
{
    /**
     * The Period Slug
     */
    public const SLUG = 'yesterday';

    /**
     * The Period Label
     */
    public const LABEL = 'Yesterday';

    /**
     * BUild
     *
     * @return void
     */
    public function build(): void {
        $this->fromDate = Carbon::now()->subDay()->startOfDay();
        $this->toDate = Carbon::now()->subDay()->endOfDay();
    }
}
