<?php

namespace App\Services\WooCommerce\DataObjects;

use App\Services\Contracts\DataObjectContract;
use App\Services\DataObjects\BaseObject;
use App\Models\Subscription\KindhumanSubscription;
use App\Models\KindCash;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Product;

class Membership extends BaseObject implements DataObjectContract
{
    /**
     * Order Schame
     *
     * @return void
     */
    protected function schema(): void {
        $this->string('status', 'active');
        $this->integer('customer_id');
        $this->string('customer_email');
        $this->array('items', []);
        $this->string('start_date', null);
        $this->string('end_date', null);
        $this->string('next_payment_date', null);
        $this->string('last_payment', null);
        $this->money('total', 0);
        $this->string('payment_method', 'stripe');
        $this->array('orders');
        $this->array('billing_address');
        $this->array('shipping_address');
        $this->string('payment_interval');
        $this->array('cause');
        $this->array('logs', []);
        $this->integer('payment_intents');
        $this->integer('active_order_id', null);
    }

    /**
     * Sync Customer
     *
     * @return WooCustomer
     */
    public function sync(): KindhumanSubscription {
        $customer = Customer::whereCustomerId($this->customer_id)->first();

        if (is_null($customer)) {
            $customer = Customer::create([
                'customer_id' => $this->customer_id,
                'email' => $this->customer_email,
                'username' => $this->customer_email
            ]);
        }

        if (! is_null($customer) && ! $customer->username) {
            $customer->username = $this->customer_email;
        }

        $customer->save();

        // Create Subscription
        $data = $this->toArray();
        unset($data['orders']);
        unset($data['logs']);
        unset($data['items']);

        $data['customer_id'] = $customer->id;
        $data['customer_email'] = $customer->email;
        $subscription = KindhumanSubscription::firstOrCreate($data);
        $subscription->save();

        if ($this->orders) {
            collect($this->orders)->map(function($order_id) use ($subscription) {
                $order = Order::whereOrderId($order_id)->first();

                if (! is_null($order)) {
                    $subscription->orders()->attach($order);
                }
            });
        }

        // Is user picked gift product, attach it to membership as well

        return $subscription;
    }
}
