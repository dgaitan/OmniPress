<?php

namespace App\Services\Analytics\Periods;

use Carbon\Carbon;

class CustomPeriod extends BasePeriod
{
    /**
     * The Period Slug
     */
    public const SLUG = 'custom';

    /**
     * The Period Label
     */
    public const LABEL = 'Custom';

    public function __construct(string $from, string $to) {
        $this->fromDate = Carbon::parse($from);
        $this->toDate = Carbon::parse($to);
    }

    /**
     * BUild
     *
     * @return void
     */
    public function build(): void {}
}
