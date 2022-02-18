<?php

namespace App\Services\WooCommerce\DataObjects;

use App\Services\Contracts\DataObjectContract;
use App\Services\DataObjects\BaseObject;
use App\Services\WooCommerce\DataObjects\ProductCategory;
use App\Services\WooCommerce\DataObjects\ProductTag;
use App\Services\WooCommerce\DataObjects\ProductImage;
use App\Services\WooCommerce\DataObjects\ProductAttribute;
use App\Services\WooCommerce\DataObjects\ProductSetting;
use App\Models\WooCommerce\Product as WooProduct;

class Product extends BaseObject implements DataObjectContract
{
    /**
     * Constructor.
     *
     * @param array $attributes
     */
    public function __construct(public array $attributes) {
        parent::__construct($attributes);

        $this->attributes['settings'] = (new ProductSetting((array) $attributes))->toArray();
    }

    /**
     * Order Schame
     *
     * @return void
     */
    protected function schema(): void {
        $this->integer('id');
        $this->string('name');
        $this->string('slug');
        $this->string('permalink');
        $this->string('date_created', null);
        $this->string('date_modified', null);
        $this->string('type');
        $this->string('status');
        $this->boolean('featured');
        $this->string('sku');
        $this->money('price');
        $this->money('regular_price');
        $this->money('sale_price');
        $this->boolean('on_sale');
        $this->boolean('downloadable');
        $this->boolean('manage_stock');
        $this->boolean('purchasable');
        $this->integer('stock_quantity');
        $this->string('stock_status');
        $this->boolean('sold_individually');
        $this->integer('parent_id', null);
        $this->array('categories');
        $this->array('tags');
        $this->array('images');
        $this->array('attributes');
        $this->array('meta_data');
        $this->array('settings');
    }

    /**
     * Sync Customer
     *
     * @return WooProduct
     */
    public function sync(): WooProduct {
        $product = WooProduct::firstOrNew(['product_id' => $this->id]);
        $data = $this->toArray('product_id');
        unset($data['categories']);
        unset($data['tags']);
        unset($data['images']);
        unset($data['attributes']);

        $data['meta_data'] = $this->getMetaData();

        $product->fill($data);
        $product->save();

        $this->syncCollection('categories', 'product_id', ProductCategory::class, $product);
        $this->syncCollection('tags', 'product_id', ProductTag::class, $product);
        $this->syncCollection('images', 'product_id', ProductImage::class, $product);
        $this->syncCollection('attributes', 'product_id', ProductAttribute::class, $product);

        $product->save();

        return $product;
    }
}
