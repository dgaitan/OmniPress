<?php

namespace App\Imports;

use App\Services\WooCommerce\Factories\MembershipFactory;
use App\Models\KindCash;
use App\Models\Membership;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Product;
use App\Models\WooCommerce\Order;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class MembershipImport implements ToModel, WithProgressBar, WithHeadingRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $row['order_ids'] = json_decode($row['order_ids'], true);
        $row['kind_cash'] = json_decode($row['kind_cash']);

        $membership = MembershipFactory::make($row);
        $membership = $membership->sync();

        return $membership;
        // $customer = Customer::firstOrNew([
        //     'customer_id' => $row['customer_id'],
        //     'email' => $row['customer_email']
        // ]);

        // if ($customer->customer_id === 1187) {
        //     $customer->email = 'sinstone@sandiego.edu';
        //     $customer->username = $customer->email;
        // }

        // if (!$customer->username) {
        //     $customer->username = $row['customer_email'];
        // }

        // $customer->save();

        // // Get or create the order. Probably at this instance
        // // the order will not exists.
        // $order_ids = unserialize($row['order_ids']);
        // $orders = array();

        // // Create Membership
        // $membership = Membership::firstOrCreate([
        //     'price' => (int) $row['price'],
        //     'customer_id' => $customer->id,
        //     'customer_email' => $customer->email,
        //     'start_at' => (new Carbon(strtotime($row['start_at'])))->toDateString(),
        //     'end_at' => (new Carbon(strtotime($row['end_at'])))->toDateString(),
        //     'status' => $row['status'],
        //     'shipping_status' => $row['shipping_status'],
        //     'pending_order_id' => $row['pending_order_id'],
        //     'user_picked_gift' => true,
        //     'gift_product_id' => $row['gift_product_id'],
        //     'product_id' => $row['product_id']
        // ]);

        // $kind_cash = json_decode($row['kind_cash'], true);
        // // Create the kind cash related to this membeship
        // $kindCash = KindCash::create([
        //     'points' => $kind_cash['points'],
        //     'last_earned' => $kind_cash['last_earned']
        // ]);

        // // Attach it to membership
        // $membership->kindCash()->save($kindCash);
        // $membership->save();

        // if ($kind_cash['logs'] && is_array($kind_cash['logs'])) {

        //     foreach ($kind_cash['logs'] as $log) {
        //         $points = explode('-', $log['points']);
        //         $points = end($points);
        //         $points = (int) ((float) $points * 100);

        //         if (count(explode('earned', $log['event'])) === 2 ) {
        //             $membership->kindCash->addEarnLog($points, $log['event'], $log['date']);
        //             continue;
        //         }

        //         if (count(explode('redeemed', $log['event'])) === 2 ) {
        //             $membership->kindCash->addRedeemedLog($points, $log['event'], $log['date']);
        //             continue;
        //         }

        //         if ('Rewards initialized' === $log['event']) {
        //             $membership->kindCash->addInitialLog($log['event'], $log['date']);
        //             continue;
        //         }
        //     }
        // }

        // if ($kind_cash['history'] && is_array($kind_cash['logs'])) {
        //     foreach ($kind_cash['history'] as $log) {
        //         $points = (int) ((float) $log['points'] * 100);

        //         $membership->kindCash->addOrderLog($points, (int) $log['order']['ID'], $log['date']);
        //     }
        // }


        // if ($order_ids) {
        //     foreach ($order_ids as $order_id) {
        //         $order = Order::firstOrCreate(['order_id' => $order_id]);
        //         // Attach membership to order
        //         $order->update(['membership_id' => $membership->id]);
        //     }
        // }

        // // Is user picked gift product, attach it to membership as well
        // if (!is_null($membership->gift_product_id)) {
        //     $giftProduct = Product::firstOrCreate(['product_id' => $membership->gift_product_id]);
        //     $membership->giftProducts()->attach($giftProduct);
        //     $membership->save();
        // }

        // return $membership;
    }
}
