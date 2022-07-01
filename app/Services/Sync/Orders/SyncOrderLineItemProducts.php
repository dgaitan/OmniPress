<?php

namespace App\Services\Sync\Orders;

use App\Models\WooCommerce\Order;
use App\Services\BaseService;

class SyncOrderLineItemProducts extends BaseService
{
    /**
     * Undocumented function
     *
     * @param [type] $content_type
     * @param [type] $element_id
     */
    public function __construct(public Order $order)
    {}

    /**
     * Handle the Service
     *
     * @return void
     */
    public function handle()
    {
        $api = \App\Services\WooCommerce\WooCommerceService::make();

        $this->order->items->map(function ($item) use ($api) {
            $productId = $item->product->product_id;

            if ($item->product->type === 'variation') {
                $productId = $item->product->parent->product_id;
            }

            $api->products()->findAndSync($productId);
        });
    }
}
