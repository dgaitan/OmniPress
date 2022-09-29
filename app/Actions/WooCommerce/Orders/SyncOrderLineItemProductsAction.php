<?php

namespace App\Actions\WooCommerce\Orders;

use App\Models\WooCommerce\Order;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class SyncOrderLineItemProductsAction
{
    use AsAction;

    public function handle(Order $order)
    {
        try {
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
        } catch (Throwable $e) {
            Log::error(
                sprintf(
                    'Error on SyncOrderLineItemProducts action class. Info: %s',
                    $e->getMessage()
                )
            );
        }
    }

    public function asJob(Order $order)
    {
        $this->handle($order);
    }
}
