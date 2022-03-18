<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\WooCommerce\Order;
use App\Http\Resources\OrderResource;

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
        $orders = Order::with('customer');

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

        // Search
        $search = $this->analyzeSearchQuery($request, ['order_id', 'total']);
        if ($search->isValid) {
            // If the search query isn't specific
            if (!$search->specific) {
                $s = $search->s;
                // $orders = Order::search($s);
                $orders->orWhereHas('customer', function ($query) use ($s) {
                    $query->where('first_name', 'ilike', "%$s%")
                        ->orWhere('last_name', 'ilike', "%$s%")
                        ->orWhere('email', 'ilike', "%$s%")
                        ->orWhere('username', 'ilike', "%$s%");
                });

                $orders->orWhere('order_id', 'ilike', "%$s%");
                $orders->orWhere('total', 'ilike', "%$s%");
            } else {
                $orders->where($search->key, 'ilike', "$search->s%");
            }
        }

        // Filter By Status
        if ($request->input('status') && 'all' !== $request->input('status')) {
            $status = $request->input('status');
            $orders->where('status', $status);
        }

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
            '_status' => $status,
            'statuses' => $statuses,
            '_order' => $request->input('order') ?? 'desc',
            '_orderBy' => $request->input('orderBy') ?? ''
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
        $order = Order::with('items', 'customer')
            ->whereOrderId($id)
            ->first();

        if (is_null($order)) {
            abort(404);
        }

        return Inertia::render('Orders/Detail', [
            'order' => $order->toArray(true)
        ]);
    }
}
