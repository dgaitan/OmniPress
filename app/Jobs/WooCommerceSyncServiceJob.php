<?php

namespace App\Jobs;

use App\Services\WooCommerce\WooCommerceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WooCommerceSyncServiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Per Page
     *
     * @var integer
     */
    protected int $perPage = 100;

    /**
     * Current page
     *
     * @var integer
     */
    protected int $page = 1;

    /**
     * Resource
     *
     * @var string
     */
    protected string $resource = '';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $resource, int $perPage = 100, int $page = 1)
    {
        $this->resource = $resource;
        $this->perPage = $perPage;
        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $woocommerce = WooCommerceService::make();
        $woocommerce->{$this->resource}()->syncAll($this->perPage, $this->page);
    }
}
