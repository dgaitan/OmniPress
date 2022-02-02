<?php

namespace App\Tasks\WooCommerce;

use App\Models\WooCommerce\Customer;

class CustomerTask extends BaseTask {
    
    /**
     * The task name accessor
     * 
     * @var string
     */
    protected string $name = 'customers';

    /**
     * Main task after running initial tasks
     * 
     * @param mixed $data
     * @return void
     */
    public function handle($data): void {
        $customer = Customer::firstOrNew([
            'customer_id' => $data->customer_id,
            'email' => $data->email
        ]);
        $data = $data->toStoreData();

        $meta_data = [];
        if ($data['meta_data'] && is_array($data['meta_data'])) {
            foreach ($data['meta_data'] as $meta) {
                if ($meta->key === '_stripe_customer') continue;
                $meta_data[] = $meta;
            }
        }
        
        $data['meta_data'] = $meta_data;
        $customer->fill($data);
        $customer->save();
    }
}