<?php

namespace App\Jobs\Printforia;

use App\Models\Printforia\PrintforiaOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncOrdersIfCompleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected PrintforiaOrder $printforiaOrder;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PrintforiaOrder $printforiaOrder)
    {
        $this->printforiaOrder = $printforiaOrder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->printforiaOrder->status !== 'shipped') {
            return;
        }

        $api = \App\Services\WooCommerce\WooCommerceService::make();
        $api->orders()->update(
            $this->printforiaOrder->order->order_id,
            ['status' => 'completed'],
            true
        );
    }
}
