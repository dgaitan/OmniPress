<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\KindCash;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Order;
use App\Jobs\SingleWooCommerceSync;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MembershipController extends Controller
{
    /**
     * Get membereships
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request) {
        return ['data' => 'foo'];
    }

    /**
     * Create a new membership
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function new(Request $request) {
        $request->validate([
            'price' => ['required'],
            'customer_id' => ['required'],            
            'email' => ['required', 'email:rfc,dns'],
            'username' => ['required'],
            'order_id' => ['required']
        ]);

        $customer = Customer::firstOrNew([
            'customer_id' => $request->customer_id,
            'email' => $request->email
        ]);

        $customer->fill($request->only(['username']));
        $customer->save();

        $order = Order::firstOrNew([
            'order_id' => $request->order_id
        ]);
        
        $membership = Membership::create([
            'price' => $request->price,
            'customer_id' => $customer->id,
            'customer_email' => $customer->email,
            'start_at' => Carbon::now(),
            'end_at' => Carbon::now()->addYear(),
            'status' => 'active',
            'shipping_status' => 'pending',
            'pending_order_id' => 0,
        ]);

        $kindCash = KindCash::create([
            'points' => 2.0,
            'last_earned' => 2.0
        ]);

        $membership->kindCash()->save($kindCash);
        $kindCash->addInitialLog();

        $order->update(['membership_id' => $membership->id]);

        SingleWooCommerceSync::dispatch('customers', $customer->customer_id);
        SingleWooCommerceSync::dispatch('orders', $request->order_id);

        return response()->json(['membership' => $membership->toArray()]);
    }
}
