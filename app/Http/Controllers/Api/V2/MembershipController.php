<?php

namespace App\Http\Controllers\Api\V2;

use App\Actions\Memberships\UpdateClientKindCashAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Memberships\MembershipResource;
use App\Http\Resources\Api\V2\Memberships\MembershipCollection;
use App\Http\Resources\Api\V2\Memberships\MembershipOrdersCollection;
use App\Models\Membership;
use App\Models\User;
use App\Models\WooCommerce\Order;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        // Filter By Date.
        if (
            $request->input('fromDate') || $request->input('toDate')
        ) {
            $dateFieldToFilter = in_array($request->input('dateFieldToFilter'), ['start_at', 'end_at'])
                ? $request->input('dateFieldToFilter')
                : 'start_at';

            $fromDate = !empty($request->input('fromDate'))
                ? Carbon::parse($request->input('fromDate'))
                : null;

            $toDate = !empty($request->input('toDate'))
                ? Carbon::parse($request->input('toDate'))
                : Carbon::now();

            if ($fromDate && $toDate) {
                $memberships->whereBetween($dateFieldToFilter, [$fromDate, $toDate]);
            } elseif (is_null($fromDate)) {
                $memberships->where($dateFieldToFilter, '<=', $toDate);
            }
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

    /**
     * Bulk Actions
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkActions(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'bulkAction' => 'required',
            'ids' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Something went wrong',
                'errors' => $validator->errors()
            ]);
        }

        $data = $validator->safe()->all();
        $action = $data['bulkAction'];
        $action = explode('_to_', $action);
        $message = '';

        if (count($action) === 2 && in_array($action[0], ['shipping_status', 'status'])) {
            $memberships = Membership::whereIn('id', $data['ids'])->get();
            if ($memberships->isNotEmpty()) {
                $memberships->map(fn ($m) => $m->update([$action[0] => $action[1]]));
                $status = explode('_', $action[0]);
                $status = implode(' ', $status);
                $_to = explode('_', $action[1]);
                $_to = implode(' ', $_to);
                $message = sprintf(
                    'Memberships updated to %s',
                    $_to
                );
            }
        }

        return response()->json([
            'message' => $message,
        ]);
    }

    public function updateKindCash(Request $request) {
        $validator = Validator::make($request->all(), [
            'membership_id' => 'required|int',
            'amount' => 'required|numeric',
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json(data: [
                'message' => 'Something went wrong',
                'errors' => $validator->errors()
            ], status: 403);
        }

        $membership = Membership::find($request->membership_id);
        if (!$membership) {
            return response()->json(data: [
                'message' => 'Something went wrong',
                'errors' => 'Invalid Membership'
            ], status: 404);
        }

        $emailWhiteList = [
            'info@kindhumans.com',
        ];

        if (in_array($request->email, $emailWhiteList)) {
            $user = User::whereEmail('dgaitan@kindhumans.com')->first();
        } else {
            $user = User::whereEmail($request->email)->first();
        }

        if (!$user) {
            return response()->json(data: [
                'message' => 'Something went wrong',
                'errors' => 'Email is invalid.'
            ], status: 403);
        }

        $membership->updateCash(
            cash: $request->amount,
            addedBy: $user->email
        );

        // Sending kindcash to kindhumans store.
        UpdateClientKindCashAction::dispatch(membership: $membership);

        return response()->json(data: [
            'message' => 'Kindcash updated',
            'kindCash' => $membership->kindCash->toArray()
        ]);
    }
}
