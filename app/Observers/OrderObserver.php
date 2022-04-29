<?php

namespace App\Observers;

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
    }
}
