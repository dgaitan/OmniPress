<?php

namespace App\Tasks\WooCommerce;

use App\Models\WooCommerce\Product;
use App\Models\WooCommerce\ProductImage;
use App\Models\WooCommerce\Category;

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

        // First Sync the product images
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

        // Sync Categories
        if ($data->categories) {
            $categoriesToAttach = []; // An array to store the categories to attach
            
            // Loop the categories that comes from the API
            foreach ($data->categories as $category) {
                $catData = $category->toStoreData(); // Convert the data to an assoc array.
                $_cat = Category::firstOrNew(['woo_category_id' => $category->category_id]); // Find or Create a new category
                $_cat['woo_category_id'] = $category->category_id;
                $_cat->fill($catData);
                $_cat->save();

                $categoriesToAttach[] = $_cat->id;
            }

            // // Maybe a category doesn't exist anymore on this product?
            // // Lets compare the current categories attached and the new ones to attach.
            // $categoriesToDetach = [];
            // foreach ($currentCategoriesIds as $catId) {
            //     // If a current category Id does not exists in the
            //     // categories to attach, it means that this does not
            //     // have relation with this product anymore.
            //     if (!in_array($catId, $categoriesToAttach)) {
            //         $categoriesToDetach[] = $catId;
            //     }
            // }
            
            // Attach and detach categories
            $product->categories()->sync($categoriesToAttach);
        }
    }
}