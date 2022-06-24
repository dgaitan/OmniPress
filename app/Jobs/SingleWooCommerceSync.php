<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SingleWooCommerceSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id = 0;

    protected $resource = '';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $element_id, string $resource)
    {
        $this->id = $element_id;
        $this->resource = $resource;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $api = \App\Services\WooCommerce\WooCommerceService::make();
        $api->{$this->resource}()->findAndSync($this->id);
    }
}
