<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\KindCash;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Product;
use App\Jobs\Memberships\NewMembershipJob;
use App\Jobs\Memberships\SyncNewMemberOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        $memberships = Membership::with('customer', 'kindCash')
            ->orderBy('start_at', 'desc')
            ->get();

        $data = [];

        if ($memberships->count() > 0) {
            $data = $memberships->map(fn($m) => $m->toArray());
        }

        return response()->json($data, 200);
    }

    /**
     * [show description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function show(Request $request, $id) {
        $membership = Membership::find($id);

        if (!is_null($membership)) {
            return response()->json($membership->toArray(true), 200);
        }

        return response()->json(['message' => "Membership not found"], 404);
    }

    /**
     * Create a new membership
     *
     * To create a new membership we need the following params:
     *
     * price - integer
     * customer_id - integer
     * email - string
     * username - string
     * order_id - integer
     * points - integer
     * gift_product_id - integer
     * product_id - integer
     *
     * The Customer.
     * After validated the data we try to get the customer.
     * In most general cases the customer will not exists.
     * So having said this, probably we're going to create
     * the new customer.
     *
     *
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function new(Request $request) {
        // Validate the params
        $validator = Validator::make($request->all(), [
            'price' => 'required|integer',
            'customer_id' => 'required|integer',
            'email' => 'required|email:rfc,dns',
            'username' => 'required|string',
            'order_id' => 'required|integer',
            'points' => 'required|integer',
            'gift_product_id' => 'nullable|integer',
            'product_id' => 'nullable|integer'
        ]);

        // If validation fails, return error listed with 400 http code
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        /**
         * Probably the customer will not exists.
         * So, let's create one. We'll to sync it
         * later.
         */
        $customer = Customer::firstOrNew([
            'customer_id' => $request->customer_id,
            'email' => $request->email
        ]);
        $customer->username = $request->username;
        $customer->save();

        // Get or create the order. Probably at this instance
        // the order will not exists.
        $order = Order::firstOrNew([
            'order_id' => $request->order_id
        ]);
        $order->save();

        // Create Membership
        $membership = Membership::create([
            'price' => (int) $request->price,
            'customer_id' => $customer->id,
            'customer_email' => $customer->email,
            'start_at' => Carbon::now(),
            'end_at' => Carbon::now()->addYear(),
            'status' => 'active',
            'shipping_status' => 'pending',
            'pending_order_id' => 0,
            'user_picked_gift' => !is_null($request->gift_product_id),
            'gift_product_id' => $request->gift_product_id,
            'product_id' => $request->product_id
        ]);

        // Initialize the params to add a log to membership.
        $logParams = [
            'customer_id' => $customer->id,
            'order_id' => $order->id
        ];

        // Create the kind cash related to this membeship
        $kindCash = KindCash::create([
            'points' => (int) $request->points,
            'last_earned' => (int) $request->points
        ]);


        // Attach it to membership
        $membership->kindCash()->save($kindCash);
        $kindCash->addInitialLog();

        // Attach membership to order
        $order->update([
            'has_membership' => true,
            'membership_id' => $membership->id
        ]);

        // Is user picked gift product, attach it to membership as well
        if (!is_null($request->gift_product_id)) {
            $giftProduct = Product::firstOrCreate(['product_id' => $request->gift_product_id]);
            $membership->giftProducts()->attach($giftProduct);
            $membership->save();
        }

        // Create the initial log for membership
        $logParams['description'] = Membership::logMessages('created_by_checkout');
        $membership->logs()->create($logParams);

        NewMembershipJob::dispatch(
            $membership->customer_email,
            $customer->customer_id,
            $order->order_id,
            $membership->user_picked_gift
                ? $membership->gift_product_id
                : null,
            $membership->product_id ?? null
        );

        return response()->json([
            'membership' => [
                'id' => $membership->id,
                'start_at' => $membership->start_at,
                'end_at' => $membership->end_at,
                'status' => $membership->status,
                'shipping_status' => $membership->shipping_status
            ],
            'kind_cash' => [
                'points' => $kindCash->points,
                'last_earned' => $kindCash->last_earned
            ]
        ], 200);
    }

    /**
     * Renew a membership from kindhumans
     *
     * @param Request $request
     * @return void
     */
    public function renew(Request $request) {
        // Validate the params
        $validator = Validator::make($request->all(), [
            'membership_id' => 'required|integer',
            'order_id' => 'required|integer',
            'gift_product_id' => 'required|integer',
        ]);

        // If validation fails, return error listed with 400 http code
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $membership = Membership::find($request->membership_id);

        if (is_null($membership)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Membership Not Found'
            ]);
        }

        $membership->status = Membership::ACTIVE_STATUS;
        $membership->shipping_status = Membership::SHIPPING_PENDING_STATUS;
        $membership->pending_order_id = $request->order_id;
        $membership->gift_product_id = $request->gift_product_id;
        $membership->end_at = $membership->end_at->addYear();
        $membership->payment_intents = 0;
        $membership->save();
        $membership->sendMembershipRenewedMail();

        $membership->kindCash->addCash(70, "Cash earned by membership renewal");

        SyncNewMemberOrder::dispatch($membership->id, $request->order_id);

        return response()->json([
            'status' => 'success',
            'membership' => $membership->toArray(true)
        ]);
    }

    /**
     * Set the new Gift
     *
     * @param Request $request
     * @return array
     */
    public function pickGift(Request $request) {
        // Validate the params
        $validator = Validator::make($request->all(), [
            'membership_id' => 'required|integer',
            'gift_product_id' => 'required|integer',
        ]);

        // If validation fails, return error listed with 400 http code
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $membership = Membership::find($request->membership_id);

        if (is_null($membership)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Membership Not Found'
            ]);
        }

        $membership->status = Membership::ACTIVE_STATUS;
        $membership->gift_product_id = $request->gift_product_id;
        $membership->save();

        return response()->json([
            'status' => 'success',
            'membership' => $membership->toArray(true)
        ]);
    }

    /**
     * [addCash description]
     *
     * @param Request $request [description]
     * @param [type]  $id      [description]
     */
    public function addCash(Request $request, $id) {
        $membership = Membership::find($id);

        if (is_null($membership)) {
            return response()->json(['error' => 'Membership not found'], 404);
        }

        // Validate the params
        $validator = Validator::make($request->all(), [
            'points' => 'required|integer',
            'message' => 'required'
        ]);

        // If validation fails, return error listed with 400 http code
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $membership->kindCash->addCash($request->points, $request->message);

        return response()->json($membership->kindCash->toArray(), 200);
    }

    /**
     * Redeem cash
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function redeemCash(Request $request, $id) {
        $membership = Membership::find($id);

        if (is_null($membership)) {
            return response()->json(['error' => 'Membership not found'], 404);
        }

        // Validate the params
        $validator = Validator::make($request->all(), [
            'points' => 'required|integer',
            'order_id' => 'required|integer',
            'message' => 'required'
        ]);

        // If validation fails, return error listed with 400 http code
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $membership->kindCash->redeemCash(
            $request->points,
            $request->message,
            $request->order_id
        );

        return response()->json($membership->kindCash->toArray(), 200);
    }


    /**
     * Check if an email has an active membership
     *
     * @param  Request $request [description]
     * @param  [type]  $email   [description]
     * @return [type]           [description]
     */
    public function checkMembershipEmail(Request $request, $email) {
        $memberships = Membership::whereCustomerEmail($email)->where('status', '!=', 'expired');

        return response()->json(['exists' => $memberships->exists()], 200);
    }
}
