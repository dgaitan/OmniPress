<?php

namespace App\Actions\WooCommerce\Orders;

use App\Models\WooCommerce\Order;
use Lorisleiva\Actions\Concerns\AsAction;

class SyncOrderLineItemProductsAction
{
    use AsAction;

    public function handle(Order $order)
    {
        $api = \App\Services\WooCommerce\WooCommerceService::make();

        $order->items->map(function ($item) use ($api) {
            if ($item->order_line_id && $item->product) {
                $productId = $item->product->product_id;

                if ($item->product->type === 'variation') {
                    $productId = $item->product->parent->product_id;
                }

                $api->products()->getAndSync($productId);
            }
        });
    }

    public function asJob(Order $order)
    {
        $this->handle($order);
    }
}
