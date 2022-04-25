<?php

namespace App\Services\WooCommerce\DataObjects;

use App\Services\Contracts\DataObjectContract;
use App\Services\DataObjects\BaseObject;
use App\Models\WooCommerce\OrderLine as WooOrderLine;
use App\Models\WooCommerce\Product;

class SubscriptionItem extends BaseObject implements DataObjectContract
{
    /**
     * Order line schema
     *
     * @return void
     */
    protected function schema(): void {
        
    }

    /**
     * Sync OrderLine
     *
     * @return WooOrderLine
     */
    public function sync(): WooOrderLine {
        $orderLine = WooOrderLine::firstOrNew(['order_line_id' => $this->id]);
        $orderLine->fill($this->toArray('order_line_id'));

        $product_id = $this->variation_id > 0
            ? $this->variation_id
            : $this->product_id;
        $product = Product::whereProductId($product_id)->first();

        if ($product) {
            $orderLine->product_id = $product->id;
        }

        $orderLine->save();

        return $orderLine;
    }
}
