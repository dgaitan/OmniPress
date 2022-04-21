<?php

namespace App\Http\Controllers;

use App\Exports\MembershipExport;
use App\Models\Membership;
use App\Models\WooCommerce\Product;
use App\Http\Resources\MembershipResource;
use App\Jobs\Memberships\ManualRenewMembershipJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class MembershipController extends Controller
{
    /**
     * Membership Index View
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        $cacheKey = "membeships_index_";
        $perPage = 50;

        if ($request->input('page')) {
            $cacheKey = $cacheKey . $request->input('page') . "_";
        }

        [$cacheKey, $memberships] = $this->queryset($request, $cacheKey);

        if (Cache::tags('memberships')->has($cacheKey)) {
            $memberships = Cache::tags('memberships')->get($cacheKey, []);
        } else {
            $memberships = Cache::tags('memberships')
                ->remember($cacheKey, 3600, function () use ($memberships, $perPage) {
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
            '_status' => $request->has('status') ? $request->status : '',
            '_shippingStatus' => $request->input('shippingStatus') ?? '',
            '_order' => $request->input('order') ?? 'desc',
            '_orderBy' => $request->input('orderBy') ?? '',
            '_fromDate' => $request->input('fromDate') ?? '',
            '_toDate' => $request->input('toDate') ?? '',
            '_dateFieldToFilter' => $request->input('dateFieldToFilter') ?? 'start_at'
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
        // Cache::tags('membership')->flush();
        $cacheKey = "membership_" . $id;
        $membership = null;

        if (Cache::tags('memberships')->has($cacheKey)) {
            $membership = Cache::tags('memberships')->get($cacheKey);
        } else {
            $membership = Cache::tags('memberships')
                ->remember($cacheKey, now()->addDay(), function() use ($id) {
                    $m = Membership::with(['customer', 'kindCash'])->find($id);
                    return new MembershipResource($m);
                });
        }

        if (is_null($membership)) {
            abort(404);
        }

        return Inertia::render('Memberships/Detail', [
            'data' => $membership,
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
            'end_at' => ['required', 'date'],
            'last_payment_intent' => ['nullable', 'date'],
            'points' => ['required', 'numeric']
        ])->validateWithBag('updateMembership');

        $membership = Membership::find($id);

        if (is_null($membership)) {
            abort(404);
        }

        $data = [
            'status' => $request->input('status'),
            'shipping_status' => $request->input('shipping_status'),
            'end_at' => (new Carbon(strtotime($request->input('end_at'))))->toDateTimeString()
        ];

        if ($request->user()->can('force_membership_renewals')) {
            $data['last_payment_intent'] = Carbon::parse($request->last_payment_intent);
        }

        $membership->update($data);


        $points = (int) ((float) $request->input('points') * 100);

        if ($points !== $membership->kindCash->points) {
            $membership->kindCash->update([
                'points' => $points
            ]);
            $membership->kindCash->addLog('earned', $points, sprintf(
                'Kind Cash added by %s',
                $request->user()->email
            ));
        }

        Cache::tags('memberships')->flush();

        return back();
    }

    /**
     * Update Membership Kind Cash
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function updateKindCash(Request $request, $id) {
        Validator::make($request->all(), [
            'points' => ['required', 'numeric']
        ])->validateWithBag('updateKindCash');

        $membership = Membership::find($id);

        if (is_null($membership)) {
            abort(404);
        }

        $points = (int) ((float) $request->input('points') * 100);
        $membership->kindCash->update([
            'points' => $points
        ]);
        $membership->kindCash->addLog('earned', $points, sprintf(
            'Kind Cash added by %s',
            $request->user()->email
        ));

        Cache::tags('memberships')->flush();

        return back();
    }

    /**
     * Export Memberships View
     *
     * @param Request $request
     * @return void
     */
    public function export(Request $request)
    {
        $cacheKey = "memberships_csv_";
        [$cacheKey, $memberships] = $this->queryset($request, $cacheKey);
        $memberships = $memberships->get();
        $filename = sprintf(
            "kindhumans_memberships_%s_%s.csv",
            $memberships->count(),
            Carbon::now()->format('Y-m-d-His')
        );

        $memberships = $memberships->map(function ($m) {
            return [
                'id' => $m->id,
                'customer' => sprintf(
                    '%s - %s',
                    $m->customer->username,
                    $m->customer->email
                ),
                'status' => $m->status,
                'shipping_status' => $m->shipping_status,
                'giftProduct' => $m->gift_product_id
                    ? Product::whereProductId($m->gift_product_id)->pluck('name')[0]
                    : '-',
                'start_at' => $m->start_at->format('F j, Y'),
                'end_at' => $m->end_at->format('F j, Y'),
                'kindCash' => $m->kindCash->cashForHuman(),
            ];
        })->toArray();

        return Excel::download(
            new MembershipExport($memberships),
            $filename,
            \Maatwebsite\Excel\Excel::CSV,
            [
                'Content-Type' => 'text/csv',
            ]
        );
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

        // Custom Actions Memberships
        if (count($action) === 1) {
            $action = end($action);
            $memberships = Membership::whereIn('id', $request->input('ids'));

            // Expiring
            if ($action === 'expire' && $memberships->exists()) {
                $memberships->get()->map(function($m) use ($request) {
                    $m->expire(sprintf(
                        "Membership Expired by: %s", $request->user()->email
                    ));
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

        Cache::tags('memberships')->flush();

        return to_route('kinja.memberships.index', $request->input('filters', []))->banner($message);
    }

    /**
     * Action that should be handled by QA only.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function testManuallyRenew(Request $request, $id) {
        $membership = Membership::find($id);

        if (! $request->user()->can('force_membership_renewals')) {
            return abort(403);
        }

        if (is_null($membership)) {
            return abort(404);
        }

        if ($membership->isActive()) {
            ManualRenewMembershipJob::dispatch($membership->id);
        }

        $message = 'Membership manual review has been started in the background. Please wait a few seconds until the processes has been finished.';

        session()->flash('flash.banner', $message);
        session()->flash('flash.bannerStyle', 'success');
        return back()->banner($message);
    }

    /**
     * Prepare Membership Queryset with filters.
     *
     * @param Request $request
     * @param string $cacheKey
     * @return array
     */
    protected function queryset(Request $request, string $cacheKey): array {
        $memberships = Membership::with(['customer', 'kindCash']);

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

        // Filter By Date.
        if (
            $request->input('fromDate') || $request->input('toDate')
        ) {
            $dateFieldToFilter = in_array($request->input('dateFieldToFilter'), ['start_at', 'end_at'])
                ? $request->input('dateFieldToFilter')
                : 'start_at';

            $fromDate = ! empty($request->input('fromDate'))
                ? Carbon::parse($request->input('fromDate'))
                : null;

            $toDate = ! empty($request->input('toDate'))
                ? Carbon::parse($request->input('toDate'))
                : Carbon::now();

            if ($fromDate && $toDate) {
                $memberships->whereBetween($dateFieldToFilter, [$fromDate, $toDate]);
                $cacheKey = sprintf(
                    '%s_date_%s_from_%s_to_%s',
                    $cacheKey,
                    $dateFieldToFilter,
                    $fromDate->format('Y-m-d'),
                    $toDate->format('Y-m-d')
                );

            } else if (is_null($fromDate)) {
                $memberships->where($dateFieldToFilter, '<=', $toDate);
                $cacheKey = sprintf(
                    '%s_date_%s_until_%s',
                    $cacheKey,
                    $dateFieldToFilter,
                    $toDate->format('Y-m-d')
                );
            }

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
        if (
            $request->input('orderBy')
            && in_array($request->input('orderBy'), $availableOrders)
        ) {
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

        return [
            $cacheKey,
            $memberships
        ];
    }
}
