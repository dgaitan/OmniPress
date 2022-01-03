<?php

namespace App\Tasks\WooCommerce;

use App\Models\WooCommerce\Product;
use App\Models\WooCommerce\ProductImage;

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
    protected function handle($data): void {
        $product = Product::firstOrNew(['product_id' => $data->product_id]);
        $product->fill($data->toStoreData());
        $product->save();
        
        if ($data->images) {
            foreach ($data->images as $image) {
                $imageData = $image->toStoreData();
                $_image = ProductImage::firstOrNew(['product_image_id' => $image->image_id]);
                
                $imageData['product_id'] = $product->id;
                $imageData['product_image_id'] = $image->image_id;
                
                $_image->fill($imageData);
                $_image->save();
            }
        }
    }
}