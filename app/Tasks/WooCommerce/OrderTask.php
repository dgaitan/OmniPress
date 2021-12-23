<?php

namespace App\Tasks\WooCommerce;

use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Customer;

class OrderTask extends BaseTask {
    
    /**
     * The task name accessor
     * 
     * @var string
     */
    protected string $name = 'orders';

    /**
     * Main task after running initial tasks
     * 
     * @param mixed $data
     * @return void
     */
    protected function handle($data): void {
        $order = Order::firstOrNew(['order_id' => $data->order_id]);
        $data = $data->toStoreData();

        // Maybe get the customer
        if ($customer = Customer::where('customer_id', $data['customer_id'])->first()) {
            $data['customer_id'] = $customer->id;
        }

        

        $order->fill($data);
        $order->save();
    }
}