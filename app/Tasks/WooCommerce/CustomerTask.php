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
        $customer->fill($data->toStoreData());
        $customer->save();
    }
}