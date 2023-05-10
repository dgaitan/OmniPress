<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Memberships\MembershipResource;
use App\Http\Resources\Api\V2\Memberships\MembershipCollection;
use App\Http\Resources\Api\V2\Memberships\MembershipOrdersCollection;
use App\Models\Membership;
use App\Models\WooCommerce\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MembershipController extends Controller {

    /**
     * Main Index List View
     *
     * @param Request $request
     * @return MembershipCollection
     */
    public function index(Request $request) {
        $memberships = Membership::with('customer', 'kindCash')
            ->orderBy('last_payment_intent', 'desc');

        // Filter By Status
        if ($request->input('status') && 'all' !== $request->input('status')) {
            $memberships->where('status', $request->input('status'));
        }

        // Filter By Shipping status
        if (
            $request->input('shippingStatus')
            && !empty($request->input('shippingStatus'))
            && Membership::isValidShippingStatus($request->input('shippingStatus'))
        ) {
            $memberships->where('shipping_status', $request->input('shippingStatus'));
        }

        // Search
        if ($request->input('s') && !empty($request->input('s'))) {
            $s = $request->input('s');
            $memberships->orWhereHas('customer', function ($query) use ($s) {
                $query->where('first_name', 'ilike', "%$s%")
                    ->orWhere('last_name', 'ilike', "%$s%")
                    ->orWhere('username', 'ilike', "%$s%");
            });

            $memberships->orWhere('customer_email', 'ilike', "%$s%");
            $memberships->orWhereExists(function ($query) use ($s) {
                $query->select('order_id')
                    ->from('orders')
                    ->whereColumn('orders.membership_id', 'memberships.id')
                    ->where('order_id', 'ilike', "%$s%");
            });
        }

        $memberships = $memberships->paginate(50);

        return new MembershipCollection($memberships);
    }

    /**
     * Show a membership detail
     *
     * @param Request $request
     * @param integer $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse {
        $membership = Membership::find($id);
        if (!$membership) {
            return response()->json([
                'message' => 'Membership Not Found'
            ], 404);
        }

        return response()->json(new MembershipResource($membership));
    }

    /**
     * List Membership Orders
     *
     * @return MembershipOrdersCollection
     */
    public function membershipOrders(Request $request, int $id) {
        $membership = Membership::find($id);
        if (!$membership) {
            return response()->json([
                'message' => 'Membership Not Found'
            ], 404);
        }

        $orders = Order::whereMembershipId($membership->id)->paginate(50);

        return new MembershipOrdersCollection($orders);
    }

    public function membershipKindCash(Request $request, int $id) {
        $membership = Membership::find($id);
        if (!$membership) {
            return response()->json([
                'message' => 'Membership Not Found'
            ], 404);
        }

        return response()->json($membership->kindCash);
    }
}
