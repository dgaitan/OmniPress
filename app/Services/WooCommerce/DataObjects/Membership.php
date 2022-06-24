<?php

namespace App\Services\WooCommerce\DataObjects;

use App\Models\KindCash;
use App\Models\Membership as KinjaMembership;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Product;
use App\Services\Contracts\DataObjectContract;
use App\Services\DataObjects\BaseObject;

class Membership extends BaseObject implements DataObjectContract
{
    /**
     * Order Schame
     *
     * @return void
     */
    protected function schema(): void
    {
        $this->integer('customer_id');
        $this->string('customer_email');
        $this->integer('product_id', 160768);
        $this->string('start_at', null);
        $this->string('end_at', null);
        $this->string('status', 'active');
        $this->string('shipping_status', 'pending');
        $this->money('price', 3500);
        $this->array('order_ids');
        $this->integer('gift_product_id', null);
        $this->integer('pending_order_id');
        $this->string('last_payment_intent', null);
        $this->integer('payment_intents');
        $this->array('kind_cash');
    }

    /**
     * Sync Customer
     *
     * @return WooCustomer
     */
    public function sync(): KinjaMembership
    {
        $customer = Customer::whereCustomerId($this->customer_id)->first();

        if (is_null($customer)) {
            $customer = Customer::create([
                'customer_id' => $this->customer_id,
                'email' => $this->customer_email,
                'username' => $this->customer_email,
            ]);
        }

        if (! is_null($customer) && ! $customer->username) {
            $customer->username = $this->customer_email;
        }

        $customer->save();

        // Create Membership
        $data = $this->toArray();
        unset($data['order_ids']);
        unset($data['kind_cash']);

        $data['customer_id'] = $customer->id;
        $data['customer_email'] = $customer->email;
        $membership = KinjaMembership::firstOrCreate($data);
        $membership->save();

        if ($this->kind_cash) {
            $kindCashData = [
                'points' => $this->kind_cash['points'],
                'last_earned' => $this->kind_cash['last_earned'],
            ];

            $kindCash = $membership->kindCash()->exists()
                ? $membership->kindCash()->update($kindCashData)
                : $membership->kindCash()->save(KindCash::create($kindCashData));
            $kindCash = $membership->kindCash;

            if ($this->kind_cash['logs']) {
                collect($this->kind_cash['logs'])->map(function ($log) use ($kindCash) {
                    $kindCash->logs()->create([
                        'date' => $log->date ? $log->date : \Carbon\Carbon::now(),
                        'event' => $log->event,
                        'points' => $log->points,
                        'order_id' => $log->order_id,
                        'description' => $log->description,
                    ]);
                });
            }
        }

        if ($this->order_ids) {
            collect($this->order_ids)->map(function ($order_id) use ($membership) {
                $order = Order::whereOrderId($order_id)->first();

                if (! is_null($order)) {
                    // Attach membership to order
                    $order->update([
                        'has_membership' => true,
                        'membership_id' => $membership->id,
                    ]);
                }
            });
        }

        // Is user picked gift product, attach it to membership as well
        if (
            ! is_null($membership->gift_product_id)
            && $giftProduct = Product::whereProductId($membership->gift_product_id)->first()
        ) {
            $membership->giftProducts()->attach($giftProduct);
            $membership->save();
        }

        return $membership;
    }
}
