<?php

namespace App\Http\Controllers;

use App\Models\WooCommerce\Order;
use App\Http\Resources\OrderResource;
use App\Http\Resources\Printforia\PrintforiaOrdersCollection;
use App\Models\Printforia\PrintforiaOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class OrderController extends Controller
{
    /**
     * [index description]
     * @return [type] [description]
     */
    public function index(Request $request)
    {
        $statuses = [
            ['slug' => 'processing', 'label' => 'Processing'],
            ['slug' => 'completed', 'label' => 'Completed'],
            ['slug' => 'pending', 'label' => 'Pending Payment'],
            ['slug' => 'failed', 'label' => 'Failed']
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
                    'email' => $order->customer->email
                ] : [
                    'id' => 0,
                    'name' => sprintf('%s %s', $order->billing->first_name, $order->billing->last_name),
                    'email' => $order->billing->email
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
                    'customer' => $customer
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
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        $cacheKey = sprintf("woocommerce_order_%s", $id);
        $order = Cache::tags('orders')->get($cacheKey, null);

        if (is_null($order)) {
            $order = Cache::tags('orders')->remember($cacheKey, 86430, function() use ($id) {
                $order = Order::with('items', 'customer', 'paymentMethod')
                    ->whereOrderId($id)
                    ->first();

                if (is_null($order)) {
                    abort(404);
                }

                return new OrderResource($order);
            });
        }

        return Inertia::render('Orders/Detail', [
            'order' => $order
        ]);
    }

    /**
     * It needs to be completed
     *
     * @param Request $request
     * @return void
     */
    public function export(Request $request) {
        $orders = $this->queryset($request, Order::with('customer', 'items'));
        $orders = $orders->get();
        $orders = $orders->map(function ($order) {
            return $order->items->map(function($item) use ($order) {
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
                        ? ( $order->customer->hasMemberships() ? 'Yes' : 'No' )
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
                    'subtotal' => $item->subtotal
                ];
            });
        });
    }

    /**
     * Printforia Order Index View
     *
     * @param Request $request
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
            '_orderBy' => $request->input('orderBy') ?? ''
        ]);

        return Inertia::render('Orders/PrintforiaOrders', $data);
    }

    /**
     * Process Orders Queryset
     *
     * @param Request $request
     * @return Builder
     */
    protected function queryset(Request $request, $baseQuery = null): Builder {
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

            } else if (is_null($fromDate)) {
                $orders->where('date_created', '<=', $toDate);
            }

        }

        // Search
        $search = $this->analyzeSearchQuery($request, ['order_id', 'total']);
        if ($search->isValid) {
            // If the search query isn't specific
            if (!$search->specific) {
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
