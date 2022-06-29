<?php

namespace App\Observers;

use App\Jobs\Donations\OrderWasSyncedJob;
use App\Jobs\Printforia\PrintforiaSync;
use App\Models\WooCommerce\Order;
use App\Notifications\Orders\NewOrderNotification;

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
        PrintforiaSync::dispatch($order)->delay(now()->addSeconds(40));
        OrderWasSyncedJob::dispatch($order)->delay(now()->addSeconds(30));
    }

    /**
     * Undocumented function
     *
     * @param  Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        // PrintforiaService::getOrCreatePrintforiaOrder($order);
    }
}
