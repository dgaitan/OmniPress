<?php

namespace App\Services\WooCommerce\DataObjects;

use App\Services\Contracts\DataObjectContract;
use App\Services\DataObjects\BaseObject;
use App\Services\WooCommerce\DataObjects\OrderLine;
use App\Models\WooCommerce\Order as WooOrder;
use App\Models\WooCommerce\Customer;

class Order extends BaseObject implements DataObjectContract
{
    /**
     * Order Schame
     *
     * @return void
     */
    protected function schema(): void {
        $this->integer('id');
        $this->integer('parent_id', null);
        $this->integer('number');
        $this->string('order_key');
        $this->string('created_via');
        $this->string('version');
        $this->string('status');
        $this->string('currency');
        $this->string('date_created', null);
        $this->string('date_modified', null);
        $this->money('discount_total');
        $this->money('discount_tax');
        $this->money('shipping_total');
        $this->money('shipping_tax');
        $this->money('cart_tax');
        $this->money('total');
        $this->money('total_tax');
        $this->boolean('prices_include_tax');
        $this->integer('customer_id');
        $this->string('customer_ip_address');
        $this->string('customer_user_agent');
        $this->string('customer_note');
        $this->array('billing');
        $this->array('shipping');
        $this->string('payment_method');
        $this->string('transaction_id');
        $this->string('date_paid', null);
        $this->string('date_completed', null);
        $this->string('cart_hash');
        $this->boolean('set_paid');
        $this->array('meta_data');
        $this->array('line_items');
        $this->array('tax_lines');
        $this->array('shipping_lines');
        $this->array('fee_lines');
        $this->array('coupon_lines');
        $this->array('refunds');
    }

    /**
     * Sync Order
     *
     * @return WooOrder
     */
    public function sync(): WooOrder {
        $data = $this->toArray('order_id');
        unset($data['line_items']);

        $order = WooOrder::firstOrNew(['order_id' => $this->id]);
        $order->fill($data);

        // Maybe get the customer
        if ($customer = Customer::whereCustomerId($this->customer_id)->first()) {
            $order->customer_id = $customer->id;
        }

        $order->save();

        // Sync Order Lines
        if ($this->line_items) {
            foreach ($this->line_items as $item) {
                $orderLine = (new OrderLine((array) $item))->sync();
                $orderLine->order_id = $order->id;
                $orderLine->save();
            }
        }

        return $order;
    }
}
