<?php

namespace App\Observers;

use App\Models\WooCommerce\Product;
use App\Notifications\Products\LowStockNotification;

class ProductObserver
{
    /**
     * Listen to the Product Updated event.
     *
     * @param  \App\WooCommerce\Product  $user
     * @return void
     */
    public function updated(Product $product)
    {
        if (in_array($product->stock_quantity, [1,3,5,10])) {
            // $product->notify((new LowStockNotification($product))->delay(now()->addMinute()));
        }
    }
}
