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
            $memberships = $memberships->orderBy('id', 'desc');
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

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function show(Request $request, $id) {
        $membership = Membership::find($id);

        if (is_null($membership)) {
            return response()->json([
                'membership' => [],
                'message' => 'Membership Not Found'
            ], 404);
        }

        $orders = $membership->orders()->get();
        $data = $membership->toArray(true);
        $data['orders'] = $orders;

        return response()->json([
            'membership' => $data
        ], 200);
    }

    /**
     * Membership Actions
     *
     * @param Request $request
     * @return void
     */
    public function actions(Request $request)
    {
        $message = "";

        if (! $request->user()->hasPermissionTo('edit_memberships') ) {
            abort(403);
        }

        if (! $request->has('ids')) {
            abort(402);
        }

        $action = $request->input('action');
        $action = explode( '_to_', $action );

        // Update Shipping or Status
        if (count($action) === 2 && in_array($action[0], ['shipping_status', 'status'])) {
            $memberships = Membership::whereIn('id', $request->input('ids'));

            if ($memberships->exists()) {
                $memberships->get()->map(fn($m) => $m->update([$action[0] => $action[1]]));
            }

            $status = explode("_", $action[0]);
            $status = implode(" ", $status);
            $_to    = explode('_', $action[1]);
            $_to    = implode(" ", $_to);
            $message = sprintf(
                "Memberships updated %s to %s",
                $status,
                $_to
            );
        }

        // Expering Memberships
        if (count($action) === 1) {
            $action = end($action);
            $memberships = Membership::whereIn('id', $request->input('ids'));

            if ($action === 'expire' && $memberships->exists()) {
                $memberships->get()->map(function($m) use ($request) {
                    $m->update([
                        'shipping_status' => Membership::SHIPPING_CANCELLED_STATUS,
                        'status' => Membership::EXPIRED_STATUS
                    ]);

                    $m->logs()->create([
                        'description' => sprintf("Membership Expired by %s", $request->user()->email)
                    ]);
                });

                $message = "Membership expired successfully!";
            }

            if ($action === 'renew' && $memberships->exists()) {
                $memberships->get()->map(function ($m) use ($request) {
                    $renew = $m->maybeRenew(true);

                    if ($renew instanceof Membership) {
                        $m->logs()->create([
                            'description' => sprintf("Membership Renewed Manually by %s", $request->user()->email)
                        ]);
                    }
                });

                $message = "Membership manual renew has been started in the background successfully!";
            }

            if ($action === 'run_cron') {
                \Illuminate\Support\Facades\Artisan::call('kinja:renew-membership-task');
                $message = "Membership renewal cron has started running in the background!";
            }
        }

        session()->flash('flash.banner', $message);
        session()->flash('flash.bannerStyle', 'success');

        return to_route('kinja.memberships.index', $request->input('filters', []))->banner($message);
    }
}
