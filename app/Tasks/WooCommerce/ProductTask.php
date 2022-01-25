<?php

namespace App\Tasks\WooCommerce;

use App\Models\WooCommerce\Tag;
use App\Models\WooCommerce\Product;
use App\Models\WooCommerce\Category;
use App\Models\WooCommerce\ProductImage;
use App\Models\WooCommerce\ProductAttribute;

class ProductTask extends BaseTask {
    
    /**
     * The task name accessor
     * 
     * @var string
     */
    protected string $name = 'products';

    /**
     * Main task after running initial tasks
     * 
     * @param mixed $data
     * @return void
     */
    public function handle($data): void {
        $product = Product::firstOrNew(['product_id' => $data->product_id]);
        $product->fill($data->toStoreData());
        $product->save();

        $this->syncCollection(
            'images',
            'image_id',
            $product,
            ProductImage::class,
            $data->images,
            'product_image_id',
            ['product_id' => $product->id]
        );

        $this->syncCollection(
            'attributes',
            'attribute_id',
            $product,
            ProductAttribute::class,
            $data->attributes,
            null,
            ['product_id' => $product->id]
        );
        
        $this->syncCollection(
            'categories', 
            'category_id', 
            $product, 
            Category::class, 
            $data->categories, 
            'woo_category_id'
        );

        $this->syncCollection(
            'tags',
            'tag_id',
            $product,
            Tag::class,
            $data->tags,
            'woo_tag_id'
        );
    }
}