<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\WooCommerce\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
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
        $cacheKey = "membeships_index_";
        $perPage = 50;
        $status = '';
        $memberships = Membership::with(['customer', 'kindCash']);

        if ($request->input('page')) {
            $cacheKey = $cacheKey . $request->input('page') . "_";
        }

        // Set Per Page
        if ($request->input('perPage')) {
            $perPage = $request->input('perPage');
            $cacheKey = $cacheKey . $perPage . "_";
        }

        // Filter By Status
        if ($request->input('status') && 'all' !== $request->input('status')) {
            $status = $request->input('status');
            $memberships->where('status', $status);
            $cacheKey = $cacheKey . $status . "_";
        }

        // Filter By Shipping status
        if (
            $request->input('shippingStatus')
            && ! empty($request->input('shippingStatus'))
            && Membership::isValidShippingStatus($request->input('shippingStatus'))
        ) {
            $memberships->where('shipping_status', $request->input('shippingStatus'));
            $cacheKey = $cacheKey . $request->input('shippingStatus') . "_";
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
            $cacheKey = $cacheKey . $s . "_";
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

            $cacheKey = $cacheKey . $ordering . "_";
        } else {
            $memberships = $memberships->orderBy('id', 'desc');
            $cacheKey = $cacheKey . "desc_";
        }

        if (Cache::has($cacheKey)) {
            $memberships = Cache::get($cacheKey, []);
        } else {
            $memberships = Cache::remember($cacheKey, 3600, function () use ($memberships, $perPage) {
                return $memberships->paginate($perPage);
            });
        }
        $data = $this->getPaginationResponse($memberships);
        $data = array_merge($data, [
            'memberships' => collect($memberships->items())->map(function($m) {
                $customer = $m->customer;
                $cash = $m->kindCash;

                return [
                    'id' => $m->id,
                    'status' => $m->status,
                    'shipping_status' => $m->shipping_status,
                    'start_at' => $m->start_at,
                    'end_at' => $m->end_at,
                    'cash' => [
                        'points' => $cash->points,
                    ],
                    'customer' => [
                        'username' => $customer->username,
                        'email' => $customer->email
                    ],

                ];
            }),
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
    public function show(Request $request, $id)
    {
        $membership = Membership::with(['customer', 'kindCash'])->find($id);

        if (is_null($membership)) {
            abort(404);
        }

        $orders = $membership->orders()->orderBy('id', 'desc')->get();
        $data = $membership->toArray(true);
        $data['orders'] = $orders;
        $data['giftProduct'] = Product::with(['images', 'categories'])->whereProductId($membership->gift_product_id)->first();

        return Inertia::render('Memberships/Detail', [
            'membership' => $data,
            'statuses' => Membership::getStatuses(),
            'shippingStatuses' => Membership::getShippingStatuses(),
        ]);
    }

    /**
     * Update Membership
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'status' => ['required', 'string'],
            'shipping_status' => ['required', 'string'],
            'end_at' => ['required', 'date']
        ])->validateWithBag('updateMembership');

        $membership = Membership::find($id);

        if (is_null($membership)) {
            abort(404);
        }

        $membership->update([
            'status' => $request->input('status'),
            'shipping_status' => $request->input('shipping_status'),
            'end_at' => (new Carbon(strtotime($request->input('end_at'))))->toDateTimeString()
        ]);

        return back();
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
