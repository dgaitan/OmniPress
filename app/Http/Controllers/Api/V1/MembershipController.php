<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\KindCash;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Order;
use App\Jobs\SingleWooCommerceSync;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;

class MembershipController extends Controller
{
    /**
     * Get membereships
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request) {
        $memberships = Membership::with('customer')->orderBy('start_at', 'desc')->get()->map(function ($m) {
            $data = $m->toArray();
            $data['customer'] = $m->customer;
            $data['cash'] = $m->kindCash;

            return $data;
        });
        return response()->json([
            'data' => $memberships
        ]);
    }

    public function show(Request $request, $id) {
        try {
            $membership = Membership::findOrFail($id);
            return response()->json([
                'data' => $membership->toArray()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'data' => $e->getMessage()
            ]);
        }
    }

    /**
     * Create a new membership
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function new(Request $request) {
        $validator = Validator::make($request->all(), [
            'price' => 'required|integer',
            'customer_id' => 'required|integer',    
            'email' => 'required|email:rfc,dns',
            'username' => 'required|string',
            'order_id' => 'required|integer',
            'points' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $validatedData = $validator->validated();

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
            'price' => (int) $request->price,
            'customer_id' => $customer->id,
            'customer_email' => $customer->email,
            'start_at' => Carbon::now(),
            'end_at' => Carbon::now()->addYear(),
            'status' => 'active',
            'shipping_status' => 'pending',
            'pending_order_id' => 0,
        ]);

        $kindCash = KindCash::create([
            'points' => (int) $request->points,
            'last_earned' => (int) $request->points
        ]);

        $membership->kindCash()->save($kindCash);
        $kindCash->addInitialLog();

        $order->update(['membership_id' => $membership->id]);

        SingleWooCommerceSync::dispatch($customer->customer_id, 'customers');
        SingleWooCommerceSync::dispatch($order->order_id, 'orders');

        return response()->json(['membership' => $membership->toArray()]);
    }
}
