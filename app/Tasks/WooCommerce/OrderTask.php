<?php

namespace App\Tasks\WooCommerce;

use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\OrderLine;
use App\Models\WooCommerce\Product;

class OrderTask extends BaseTask
{
    /**
     * The task name accessor
     *
     * @var string
     */
    protected string $name = 'orders';

    /**
     * Main task after running initial tasks
     *
     * @param  mixed  $data
     * @return void
     */
    public function handle($data): void
    {
        $order = Order::firstOrNew(['order_id' => $data->order_id]);
        $order->fill($data->toStoreData());

        // Maybe get the customer
        if ($customer = Customer::where('customer_id', $data->customer_id)->first()) {
            $order->customer_id = $customer->id;
        }

        $order->save();

        // Sync Order Lines
        if ($data->line_items) {
            foreach ($data->line_items as $item) {
                $orderLine = OrderLine::firstOrNew(['order_line_id' => $item->line_item_id]);
                $orderLine->fill($item->toStoreData());
                $product = Product::whereProductId($item->product_id)->first();

                if ($product) {
                    $orderLine->product_id = $product->id;
                }

                $orderLine->order_id = $order->id;
                $orderLine->save();
            }
        }
    }
}
