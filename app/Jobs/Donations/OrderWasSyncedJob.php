<?php

namespace App\Jobs\Donations;

use App\Jobs\SingleWooCommerceSync;
use App\Models\WooCommerce\Order;
use App\Services\DonationsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
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
        $order = DonationsService::loadOrderDonations($this->order);
        DonationsService::addOrderDonationToCustomer($order);

        if ($this->order->customer) {
            SingleWooCommerceSync::dispatch($this->order->customer->customer_id, 'customers');
        }
    }
}
