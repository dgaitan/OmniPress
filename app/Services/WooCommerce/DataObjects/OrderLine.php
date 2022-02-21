<?php

namespace App\Services\WooCommerce\DataObjects;

use App\Services\Contracts\DataObjectContract;
use App\Services\DataObjects\BaseObject;
use App\Models\WooCommerce\OrderLine as WooOrderLine;
use App\Models\WooCommerce\Product;

class OrderLine extends BaseObject implements DataObjectContract
{
    /**
     * Order line schema
     *
     * @return void
     */
    protected function schema(): void {
        $this->integer('id');
        $this->string('name');
        $this->integer('product_id');
        $this->integer('variation_id');
        $this->integer('quantity');
        $this->string('tax_class');
        $this->money('subtotal');
        $this->money('subtotal_tax');
        $this->money('total');
        $this->money('total_tax');
        $this->array('meta_data');
        $this->string('sku');
        $this->money('price');
        $this->integer('order_id');
    }

    /**
     * Sync OrderLine
     *
     * @return WooOrderLine
     */
    public function sync(): WooOrderLine {
        $orderLine = WooOrderLine::firstOrNew(['order_line_id' => $this->id]);
        $orderLine->fill($this->toArray('order_line_id'));
        $product = Product::whereProductId($this->product_id)->first();

        if ($product) {
            $orderLine->product_id = $product->id;
        }

        $orderLine->save();

        return $orderLine;
    }
}
