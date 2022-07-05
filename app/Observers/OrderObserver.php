<?php

namespace App\Observers;

use App\Jobs\Donations\OrderWasSyncedJob;
use App\Jobs\Printforia\ProcessPrintforiaOrderJob;
use App\Models\WooCommerce\Order;
use App\Notifications\Orders\NewOrderNotification;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;

class OrderObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  \App\WooCommerce\Order  $user
     * @return void
     */
    public function created(Order $order)
    {
        $order->notify((new NewOrderNotification($order))->delay(now()->addMinute()));

        Bus::chain([
            new OrderWasSyncedJob($order),
            new ProcessPrintforiaOrderJob($order),
        ])->dispatch();
    }

    /**
     * Undocumented function
     *
     * @param  Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        $cacheKey = sprintf('woocommerce_order_%s', $order->order_id);
        Cache::tags('orders')->forget($cacheKey);
    }
}
