<?php

namespace App\Jobs\Memberships;

use App\Jobs\SingleWooCommerceSync;
use App\Models\WooCommerce\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncNewMemberOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $membership_id = 0;
    protected $order_id = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $membership_id, int $order_id)
    {
        $this->membership_id = $membership_id;
        $this->order_id = $order_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        SingleWooCommerceSync::dispatchSync($this->order_id, 'orders');

        $order = Order::whereOrderId($this->order_id)->first();
        $order->update(['membership_id' => $this->membership_id]);
    }
}
