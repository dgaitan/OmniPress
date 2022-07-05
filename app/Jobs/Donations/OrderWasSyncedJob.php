<?php

namespace App\Jobs\Donations;

use App\Jobs\SingleWooCommerceSync;
use App\Models\WooCommerce\Order;
use App\Services\Donations\AssignOrderDonation;
use App\Services\Donations\AssignOrderDonationToCustomer;
use App\Services\Sync\Orders\SyncOrderLineItemProducts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderWasSyncedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected Order $order)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        SyncOrderLineItemProducts::dispatchWithoutValidations($this->order);
        AssignOrderDonation::dispatch($this->order->id);
        AssignOrderDonationToCustomer::dispatch($this->order->id);

        if ($this->order->customer) {
            SingleWooCommerceSync::dispatch($this->order->customer->customer_id, 'customers');
        }
    }
}
