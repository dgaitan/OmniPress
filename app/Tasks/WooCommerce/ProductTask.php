<?php

namespace App\Tasks\WooCommerce;

use App\Models\WooCommerce\Product;

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
        $order = Product::firstOrNew(['product_id' => $data->product_id]);
        $data = $data->toStoreData();
        

        $order->fill($data);
        $order->save();
    }
}