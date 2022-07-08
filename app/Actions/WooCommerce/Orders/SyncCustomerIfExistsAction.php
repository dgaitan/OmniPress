<?php

namespace App\Actions\WooCommerce\Orders;

use App\Jobs\SingleWooCommerceSync;
use App\Models\WooCommerce\Order;
use Lorisleiva\Actions\Concerns\AsAction;

class SyncCustomerIfExistsAction
{
    use AsAction;

    public function handle(Order $order)
    {
        // if ($order->order_id === 549799) {
        //     dd($order->customer);
        // }

        if (! is_null($order->customer)) {
            SingleWooCommerceSync::dispatchSync($order->customer->customer_id, 'customers');
        }
    }
}
