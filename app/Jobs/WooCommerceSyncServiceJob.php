<?php

namespace App\Jobs;

use App\Models\Sync;
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
     * Sync Id
     *
     * @var integer
     */
    protected int $sync_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $sync_id)
    {
        $this->sync_id = $sync_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sync = Sync::find($this->sync_id);
        $woocommerce = WooCommerceService::make();
        $woocommerce->{$sync->content_type}()->syncAll($sync->per_page, $sync->current_page, $sync->id);
    }
}
