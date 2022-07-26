<?php

namespace App\Services\Analytics\Periods;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

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
     * A Carbon Query to get the period intervals
     *
     * @return CarbonPeriod
     */
    public function getPeriodDateIntervalQuery(): CarbonPeriod;

    /**
     * Return the period in  dates
     *
     * @return Collection
     */
    public function getPeriodDateInterval(): Collection;
}
