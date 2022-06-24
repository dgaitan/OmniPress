<?php

namespace App\Services\WooCommerce\DataObjects;

use App\Models\WooCommerce\Product as WooProduct;
use App\Services\Contracts\DataObjectContract;

class ProductVariation extends Product implements DataObjectContract
{
    /**
     * Product Variation Schema
     *
     * @return void
     */
    protected function schema(): void
    {
        parent::schema();

        $this->integer('parent_id');
    }

    /**
     * Sync Customer
     *
     * @return WooProduct
     */
    public function sync(): WooProduct
    {
        $product = WooProduct::firstOrNew(['product_id' => $this->id]);
        $data = $this->toArray('product_id');
        unset($data['categories']);
        unset($data['tags']);
        unset($data['images']);
        unset($data['attributes']);

        $data['meta_data'] = $this->getMetaData();

        $product->fill($data);
        $product->save();

        $this->syncCollection('attributes', 'product_id', ProductAttribute::class, $product);
        $this->syncCollection('images', 'product_id', ProductImage::class, $product);

        $product->syncSubscription();
        $product->save();

        return $product;
    }
}
