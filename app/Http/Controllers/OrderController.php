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
    public function index(Request $request) {
        $perPage = 50;
        $statuses = [
            ['slug' => 'processing', 'label' => 'Processing'],
            ['slug' => 'completed', 'label' => 'Completed'],
            ['slug' => 'pending', 'label' => 'Pending'],
            ['slug' => 'failed', 'label' => 'Failed']
        ];
        $status = '';

        if ($request->input('perPage')) {
            $perPage = $request->input('perPage');
        }

        if ($request->input('s') && !empty($request->input('s'))) {
            $orders = Order::search($request->input('s'))->orderBy('date_created', 'desc');
        } else {
            $orders = Order::with('customer')->orderBy('date_created', 'desc');
        }

        if ($request->input('filterByStatus')) {
            $orders->where('status', $request->input('filterByStatus'));
        }

        if ($request->input('status') && 'all' !== $request->input('status')) {
            $status = $request->input('status');
            $memberships->where('status', $status);
        }
        
        $orders = $orders->paginate($perPage);

        return Inertia::render('Orders/Index', [
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
                    'shipping' => $order->ShippingAddress(),
                    'date' => $order->getDateCompleted(),
                    'customer' => $customer
                ];
            }),
            'total' => $orders->total(),
            'nextUrl' => $orders->nextPageUrl(),
            'prevUrl' => $orders->previousPageUrl(),
            'perPage' => $orders->perPage(),
            'currentPage' => $orders->currentPage(),
            's' => $request->input('s') ?? '',
            'status' => $status,
            'statuses' => $statuses
        ]);
    }
}
