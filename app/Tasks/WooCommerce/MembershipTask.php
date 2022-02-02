<?php

namespace App\Tasks\WooCommerce;

use Carbon\Carbon;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Product;
use App\Models\Membership;
use App\Models\KindCash;

class MembershipTask extends BaseTask {
    
    /**
     * The task name accessor
     * 
     * @var string
     */
    protected string $name = 'memberships';

    /**
     * Main task after running initial tasks
     * 
     * @param mixed $data
     * @return void
     */
    public function handle($data): void {
        $membership = Membership::whereCustomerEmail($data->customer_email)->first();

        if (true) {
            $customer = Customer::firstOrNew([
                'customer_id' => $data->customer_id,
                'email' => $data->customer_email
            ]);
            
            if (!$customer->username) {
                $customer->username = $data->customer_email;
            }
            
            $customer->save();

            // Get or create the order. Probably at this instance
            // the order will not exists.            
            $order_ids = unserialize($data->order_ids);
            $orders = array();

            // Create Membership
            $membership = Membership::firstOrCreate([
                'price' => $data->price,
                'customer_id' => $customer->id,
                'customer_email' => $customer->email,
                'start_at' => (new Carbon($data->start_at))->toDateString(),
                'end_at' => (new Carbon($data->end_at))->toDateString(),
                'status' => $data->status,
                'shipping_status' => $data->shipping_status,
                'pending_order_id' => $data->pending_order_id,
                'user_picked_gift' => true,
                'gift_product_id' => $data->gift_product_id
            ]);

            // Create the kind cash related to this membeship
            $kindCash = KindCash::create([
                'points' => $data->kind_cash['points'],
                'last_earned' => $data->kind_cash['last_earned']
            ]);

            // Attach it to membership
            $membership->kindCash()->save($kindCash);

            if ($data->kind_cash['logs'] && is_array($data->kind_cash['logs'])) {
                
                foreach ($data->kind_cash['logs'] as $log) {
                    $points = explode('-', $log['points']);
                    $points = end($points);
                    $points = (int) ((float) $points * 100);
                    
                    if (count(explode('earned', $log['event'])) === 2 ) {
                        $membership->kindCash->addEarnLog($points, $log['event'], $log['date']);
                        continue;
                    }

                    if (count(explode('redeemed', $log['event'])) === 2 ) {
                        $membership->kindCash->addRedeemedLog($points, $log['event'], $log['date']);
                        continue;
                    }

                    if ('Rewards initialized' === $log['event']) {
                        $membership->kindCash->addInitialLog($log['event'], $log['date']);
                        continue;
                    }                  
                }
            }

            if ($data->kind_cash['history'] && is_array($data->kind_cash['logs'])) {
                foreach ($data->kind_cash['history'] as $log) {
                    $points = (int) ((float) $log['points'] * 100);

                    $membership->kindCash->addOrderLog($points, (int) $log['order']['ID'], $log['date']);
                }
            }
            
            if ($order_ids) {
                foreach ($order_ids as $order_id) {
                    $order = Order::firstOrCreate(['order_id' => $order_id]);
                    // Attach membership to order
                    $order->update(['membership_id' => $membership->id]);
                }
            }

            // Is user picked gift product, attach it to membership as well
            if (!is_null($membership->gift_product_id)) {
                $giftProduct = Product::firstOrCreate(['product_id' => $membership->gift_product_id]);
                $membership->giftProducts()->attach($giftProduct);
                $membership->save();
            }
        }
    }
}