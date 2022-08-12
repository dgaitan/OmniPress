<?php

namespace App\Http\Controllers;

use App\Actions\WooCommerce\Orders\UpdateOrderAction;
use App\Exports\OrderUSPSExport;
use App\Http\Resources\OrderResource;
use App\Http\Resources\Printforia\PrintforiaOrdersCollection;
use App\Http\Resources\Printforia\PrintforiaResource;
use App\Jobs\SingleWooCommerceSync;
use App\Models\Printforia\PrintforiaOrder;
use App\Models\WooCommerce\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    /**
     * [index description]
     *
     * @return [type] [description]
     */
    public function index(Request $request)
    {
        $statuses = [
            ['slug' => 'processing', 'label' => 'Processing'],
            ['slug' => 'completed', 'label' => 'Completed'],
            ['slug' => 'pending', 'label' => 'Pending Payment'],
            ['slug' => 'failed', 'label' => 'Failed'],
        ];
        $status = '';
        $orders = $this->queryset($request);
        $orders = $this->paginate($request, $orders);
        $data = $this->getPaginationResponse($orders);
        $data = array_merge($data, [
            'orders' => collect($orders->items())->map(function ($order) {
                $customer = $order->customer ? [
                    'customer_id' => $order->customer->id,
                    'name' => $order->customer->getFullName(),
                    'email' => $order->customer->email,
                ] : [
                    'id' => 0,
                    'name' => sprintf('%s %s', $order->billing->first_name, $order->billing->last_name),
                    'email' => $order->billing->email,
                ];

                return [
                    'id' => $order->id,
                    'order_id' => $order->order_id,
                    'status' => $order->status,
                    'total' => $order->total,
                    'billing' => $order->billing,
                    'shipping' => $order->ShippingAddress(),
                    'date' => $order->getDateCompleted(),
                    'permalink' => $order->getPermalink(),
                    'storePermalink' => $order->getPermalinkOnStore(),
                    'customer' => $customer,
                ];
            }),
            '_s' => $request->input('s') ?? '',
            '_status' => $request->input('status') ?? 'all',
            'statuses' => $statuses,
            '_order' => $request->input('order') ?? 'desc',
            '_orderBy' => $request->input('orderBy') ?? '',
            '_fromDate' => $request->input('fromDate') ?? '',
            '_toDate' => $request->input('toDate') ?? '',
        ]);

        return Inertia::render('Orders/Index', $data);
    }

    /**
     * Show Order Detail
     *
     * @param  Request  $request
     * @param  int  $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        $cacheKey = sprintf('woocommerce_order_%s', $id);
        $order = Cache::tags('orders')->get($cacheKey, null);

        if (is_null($order)) {
            $order = Cache::tags('orders')->remember($cacheKey, 86430, function () use ($id) {
                $order = Order::with('items', 'customer', 'paymentMethod', 'printforiaOrder')
                    ->whereOrderId($id)
                    ->first();

                if (is_null($order)) {
                    abort(404);
                }

                return new OrderResource($order);
            });
        }

        return Inertia::render('Orders/Detail', [
            'order' => $order,
        ]);
    }

    /**
     * It needs to be completed
     *
     * @param  Request  $request
     * @return void
     */
    public function export(Request $request)
    {
        $orders = $this->queryset($request, Order::with('customer', 'items'));
        $orders = $orders->get();
        $orders = $orders->map(function ($order) {
            return $order->items->map(function ($item) use ($order) {
                return [
                    'id' => $order->order_id,
                    'date' => $order->date_created
                        ? $order->date_created->format('F j, Y H:i:s')
                        : '-',
                    'status' => $order->status,
                    'last_name' => $order->billing->last_name,
                    'first_name' => $order->billing->first_name,
                    'email' => $order->billing->email,
                    'active_membership' => $order->customer_id
                        ? ($order->customer->hasMemberships() ? 'Yes' : 'No')
                        : 'No',
                    'address' => sprintf(
                        '%s %s',
                        $order->billing->address_1,
                        $order->billing->address_2
                    ),
                    'zip' => $order->billing->postcode,
                    'state' => $order->billing->state,
                    'city' => $order->billing->city,
                    'country' => $order->billing->country,
                    'subtotal' => $order->getSubtotal(),
                    'coupon' => $order->getCouponCodes(),
                    'total_discount' => $order->discount_total / 100,
                    'total_tax' => $order->total_tax / 100,
                    'total_shipping' => $order->shipping_total / 100,
                    'order_amount' => $order->total / 100,
                    'total_donated' => $order->getMetaValue('total_donated_amount'),
                    'sku' => $item->sku,
                    'name' => $item->name,
                    'price' => $item->product->price / 100,
                    'qty' => $item->quantity,
                    'total_tax' => $item->subtotal_tax,
                    'subtotal' => $item->subtotal,
                ];
            });
        });
    }

    public function exportOrderWithUSPSOnly(Request $request)
    {
        $orders = Order::whereBetween('date_created', [now()->startOfYear(), now()->endOfDay()])
            ->with('items', 'customer')
            ->where('shipping_total', '!=', 0)
            ->orderBy('id', 'desc')
            ->get();

        $orders = $orders->filter(function ($order) {
            return $order->items->count() === 1 && collect($order->shipping_lines)
                ->where('method_id', 'usps')
                ->isNotEmpty();
        })->map(function ($order) {
            return [
                'id' => $order->order_id,
                'customer' => sprintf(
                    '%s %s - %s',
                    $order->billing->first_name,
                    $order->billing->last_name,
                    $order->billing->email
                ),
                'status' => $order->status,
                'order_date' => $order->date_created->format('F j, Y'),
                'shipping_total' => $order->getMoneyValue('shipping_total'),
                'total' => $order->getMoneyValue('total')
            ];
        })->toArray();

        $filename = sprintf(
            'kindhumans_orders_shipping_usps_%s_%s.csv',
            count( $orders ),
            Carbon::now()->format('Y-m-d-His')
        );

        return Excel::download(
            new OrderUSPSExport($orders),
            $filename,
            \Maatwebsite\Excel\Excel::CSV,
            [
                'Content-Type' => 'text/csv',
            ]
        );
    }

    /**
     * Simulate that we're shipping an order by shiphero
     *
     * @param  Request  $request
     * @return void
     */
    public function simulateShipHero(Request $request)
    {
        if (env('APP_ENV', 'local') === 'production') {
            return abort(403);
        }

        $order = Order::find($request->get('order_id'));

        if (is_null($order)) {
            return abort(404);
        }

        UpdateOrderAction::dispatch($order, [
            'status' => 'completed',
            'meta_data' => [
                ['key' => '_tracking_number', 'value' => Str::random(20)],
            ],
        ]);

        session()->flash('flash.banner', 'Order was shipped, please wait a few seconds to see it reflected!');
        session()->flash('flash.bannerStyle', 'success');

        return back()->banner('Order was shipped, please wait a few seconds to see it reflected!');
    }

    public function syncOrder(Request $request)
    {
        $order = Order::find($request->get('order_id'));

        if (is_null($order)) {
            return abort(404);
        }

        SingleWooCommerceSync::dispatch($order->order_id, 'orders');

        session()->flash('flash.banner', 'Sync was initialized. Please wait a few seconds to see this order updated.');
        session()->flash('flash.bannerStyle', 'success');

        return back()->banner('Sync was initialized. Please wait a few seconds to see this order updated.');
    }

    /**
     * Printforia Order Index View
     *
     * @param  Request  $request
     * @return void
     */
    public function printforiaOrders(Request $request)
    {
        $printforiaOrders = PrintforiaOrder::with('order')->orderBy('id', 'desc');

        if ($request->has('s') && ! empty($request->s)) {
            $s = $request->s;
            $printforiaOrders->where('printforia_order_id', 'ilike', "%$s%")
                ->orWhere('ship_to_address', 'ilike', "%$s%");

            $printforiaOrders->orWhereHas('order', function ($query) use ($s) {
                $query->where('order_id', 'ilike', "%$s%");
            });
        }

        // Filter By Status
        $printforiaOrders = $this->addFilterToQuery(
            request: $request,
            filterKey: 'status',
            query: $printforiaOrders,
            validFilterKeys: PrintforiaOrder::getStatusesSlugs()
        );

        $printforiaOrders = $this->paginate($request, $printforiaOrders);
        $data = $this->getPaginationResponse($printforiaOrders);
        $data = array_merge($data, [
            'orders' => new PrintforiaOrdersCollection($printforiaOrders->items()),
            '_s' => $request->input('s') ?? '',
            '_status' => $request->input('status'),
            'statuses' => PrintforiaOrder::STATUSES,
            '_order' => $request->input('order') ?? 'desc',
            '_orderBy' => $request->input('orderBy') ?? '',
        ]);

        return Inertia::render('Orders/PrintforiaOrders', $data);
    }

    /**
     * Printforia Detail
     *
     * @param  Request  $request
     * @param [type] $id
     * @return void
     */
    public function printforiaDetail(Request $request, $id)
    {
        $printforiaOrder = PrintforiaOrder::with(['order', 'items'])
            ->wherePrintforiaOrderId($id)
            ->first();

        if (is_null($printforiaOrder)) {
            abort(404);
        }

        return Inertia::render('Orders/PrintforiaDetail', [
            'order' => new PrintforiaResource($printforiaOrder),
        ]);
    }

    public function sendPrintforiaShippedEmail(Request $request)
    {
        $printforiaOrder = PrintforiaOrder::find($request->input('order_id'));
        $printforiaOrder->sendOrderHasShippedEmail();

        session()->flash('flash.banner', 'Order Email Sent.');
        session()->flash('flash.bannerStyle', 'success');

        return back()->banner('Order Email Sent.');
    }

    /**
     * Process Orders Queryset
     *
     * @param  Request  $request
     * @return Builder
     */
    protected function queryset(Request $request, $baseQuery = null): Builder
    {
        $orders = $baseQuery ?? Order::with('customer');

        // Ordering
        $availableOrders = ['order_id', 'date_completed', 'total'];

        if (
            $request->input('orderBy')
            && in_array($request->input('orderBy'), $availableOrders)
        ) {
            $ordering = in_array($request->input('order'), ['desc', 'asc'])
                ? $request->input('order')
                : 'desc';

            $orders->orderBy($request->input('orderBy'), $ordering);
        } else {
            $orders->orderBy('date_created', 'desc');
        }

        // Filter By Date.
        if (
            $request->input('fromDate') || $request->input('toDate')
        ) {
            $fromDate = ! empty($request->input('fromDate'))
                ? Carbon::parse($request->input('fromDate'))
                : null;

            $toDate = ! empty($request->input('toDate'))
                ? Carbon::parse($request->input('toDate'))
                : Carbon::now();

            if ($fromDate && $toDate) {
                $orders->whereBetween('date_created', [$fromDate, $toDate]);
            } elseif (is_null($fromDate)) {
                $orders->where('date_created', '<=', $toDate);
            }
        }

        // Search
        $search = $this->analyzeSearchQuery($request, ['order_id', 'total']);
        if ($search->isValid) {
            // If the search query isn't specific
            if (! $search->specific) {
                $s = $search->s;

                $orders->where(function ($query) use ($s) {
                    $query->orWhereHas('customer', function ($q) use ($s) {
                        $q->where('first_name', 'ilike', "%$s%")
                            ->orWhere('last_name', 'ilike', "%$s%")
                            ->orWhere('email', 'ilike', "%$s%")
                            ->orWhere('username', 'ilike', "%$s%");
                    });

                    $query->orWhere('order_id', 'ilike', "%$s%");
                    $query->orWhere('total', 'ilike', "%$s%");
                });
            } else {
                $orders->where($search->key, 'ilike', "$search->s%");
            }
        }

        // Filter By Status
        if ($request->input('status') && 'all' !== $request->input('status')) {
            $status = $request->input('status');
            $orders->where('status', $status);
        }

        return $orders;
    }
}
