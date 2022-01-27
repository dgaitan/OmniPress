<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\KindCash;
use App\Models\WooCommerce\Customer;
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
            'date_created' => ['nullable', 'date'],
            'billing' => ['required'],
            'shipping' => ['required'],
            'email' => ['required', 'email:rfc,dns'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'username' => ['required']
        ]);

        $customer = Customer::firstOrNew([
            'customer_id' => $request->customer_id,
            'email' => $request->email
        ]);

        $customer->fill($request->only(['date_created', 'first_name', 'last_name', 'username', 'billing', 'shipping']));
        $customer->save();

        $kindCash = KindCash::create([
            'points' => 2.0,
            'last_earned' => 2.0
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
            'kind_cash_id' => $kindCash->id
        ]);

        return response()->json(['membership' => $membership->toArray()]);
    }
}
