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
                    'shipping' => $order->shipping,
                    'date' => $order->date_created ? $order->date_created->format('F j, Y @ H:i:s a') : null,
                    'customer' => $customer
                ];
            }),
            'total' => $orders->total(),
            'nextUrl' => $orders->nextPageUrl(),
            'prevUrl' => $orders->previousPageUrl(),
            'perPage' => $orders->perPage(),
            'currentPage' => $orders->currentPage(),
            's' => $request->input('s') ?? '',
            'filterByStatus' => $request->input('filterByStatus') ?? ''
        ]);
    }
}
