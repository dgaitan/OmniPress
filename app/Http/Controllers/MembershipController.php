<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MembershipController extends Controller
{
    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        $perPage = 50;
        $status = '';
        $memberships = Membership::with(['customer', 'kindCash']);

        // Set Per Page
        if ($request->input('perPage')) {
            $perPage = $request->input('perPage');
        }

        // Filter By Status
        if ($request->input('status') && 'all' !== $request->input('status')) {
            $status = $request->input('status');
            $memberships->where('status', $status);
        }

        // Filter By Shipping status
        if (
            $request->input('shippingStatus')
            && ! empty($request->input('shippingStatus'))
            && Membership::isValidShippingStatus($request->input('shippingStatus'))
        ) {
            $memberships->where('shipping_status', $request->input('shippingStatus'));
        }

        // Search
        if ($request->input('s') && ! empty($request->input('s'))) {
            $s = $request->input('s');
            $memberships->orWhereHas('customer', function ($query) use ($s) {
                $query->where('first_name', 'ilike', "%$s%")
                    ->orWhere('last_name', 'ilike', "%$s%")
                    ->orWhere('username', 'ilike', "%$s%");
            });

            $memberships->orWhere('customer_email', 'ilike', "%$s%");
        }

        // Ordering
        $availableOrders = ['id', 'kind_cash', 'start_at', 'end_at'];
        if ($request->input('orderBy') && in_array($request->input('orderBy'), $availableOrders)) {
            $ordering = in_array($request->input('order'), ['desc', 'asc'])
                ? $request->input('order')
                : 'desc';

            // If the ordering is by kind cash, let's deal with it
            if ($request->input('orderBy') === 'kind_cash') {
                $memberships->join('kind_cashes', 'kind_cashes.membership_id', '=', 'memberships.id');
                $memberships->orderBy('kind_cashes.points', $ordering);
            } else {
                $memberships->orderBy($request->input('orderBy'), $ordering);
            }
        } else {
            $memberships = $memberships->orderBy('start_at', 'desc');
        }

        $memberships = $memberships->paginate($perPage);
        $data = $this->getPaginationResponse($memberships);
        $data = array_merge($data, [
            'memberships' => collect($memberships->items())->map(fn($m) => $m->toArray(false)),
            'statuses' => Membership::getStatuses(),
            'shippingStatuses' => Membership::getShippingStatuses(),
            '_s' => $request->input('s') ?? '',
            '_status' => $status,
            '_shippingStatus' => $request->input('shippingStatus') ?? '',
            '_order' => $request->input('order') ?? 'desc',
            '_orderBy' => $request->input('orderBy') ?? ''
        ]);

        return Inertia::render('Memberships/Index', $data);
    }
}
