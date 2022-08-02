<?php

namespace App\Services\Analytics;

use Illuminate\Support\Facades\Cache;

abstract class BaseAnalyticsService
{
    public const CACHE_TAG = 'analytics_stats';

    protected Period $period;

    /**
     * Per Page
     *
     * @var int
     */
    protected int $perPage = 10;

    /**
     * Build the period
     *
     * @param  string  $period
     * @param  string|null  $from
     * @param  string|null  $to
     */
    public function __construct(
        string $period = 'month_to_date',
        string $from = '',
        string $to = ''
    ) {
        $this->period = new Period($period, $from, $to);
    }

    /**
     * Get data serialised just if it's needed for views
     *
     * @param  bool  $cached
     * @return array
     */
    public function getSerializedData(bool $cached = false, int|null $perPage = null): array
    {
        if ($perPage) {
            $this->setPerPage($perPage);
        }

        return $cached ? $this->getData() : $this->getContents();
    }

    /**
     * Collect Data
     *
     * @return array
     */
    public function getData(): array
    {
        if ($this->period->getPeriod() === 'month_to_date') {
            return $this->getContents();
        }

        if (! Cache::tags(static::CACHE_TAG)->has($this->getCacheKey())) {
            Cache::tags(static::CACHE_TAG)->remember($this->getCacheKey(), now()->addYear(1), function () {
                return $this->getContents();
            });
        }

        return Cache::tags(static::CACHE_TAG)->get($this->getCacheKey());
    }

    /**
     * Get analytics content
     *
     * @return array
     */
    public function getContents(): array
    {
        return [];
    }

    /**
     * Get Cache Key
     *
     * @return string
     */
    public function getCacheKey(): string
    {
        return sprintf(
            '%s_from_%s_to_%s_%s',
            static::CACHE_TAG,
            $this->period->getFromDate()->format('Y_m_d_h_i_s'),
            $this->period->getToDate()->format('Y_m_d_h_i_s'),
            $this->getPerPage()
        );
    }

    /**
     * Set Per Page value
     *
     * @param  int  $perPage
     * @return void
     */
    public function setPerPage(int $perPage): void
    {
        $this->perPage = $perPage;
    }

    /**
     * Get per page value
     *
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }
}
