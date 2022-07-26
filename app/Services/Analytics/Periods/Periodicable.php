<?php

namespace App\Services\Analytics\Periods;

use Carbon\Carbon;

interface Periodicable
{
    /**
     * Load the dates
     *
     * @return void
     */
    public function build(): void;

    /**
     * Get the initial date of this range
     *
     * @return Carbon
     */
    public function getFromDate(): Carbon;

    /**
     * Get the end date of this.
     *
     * @return Carbon
     */
    public function getToDate(): Carbon;

    /**
     * Return the period in  dates
     *
     * @return array
     */
    public function getDatePeriodInterval(): array;
}
