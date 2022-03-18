<?php

namespace App\Http\Controllers;

use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Dashboard View
     *
     * @param Request $request
     * @return void
     */
    public function dashboard(Request $request) {
        $stats = [
            'orders' => $this->getOrderStats(),
            'customers' => $this->getCustomerStats()
        ];

        return Inertia::render('Dashboard', $stats);
    }

    /**
     * Get Order Stats
     *
     * @return array
     */
    protected function getOrderStats(): array {
        $stats = [];

        // Current Orders
        $ordersQuery = Order::whereBetween(
            'date_created',
            [(new Carbon)->startOfMonth(), Carbon::now()]
        )->whereIn('status', ['processing', 'completed']);

        $stats['total_orders'] = $ordersQuery->count();
        $stats['total_sold'] = $ordersQuery->sum('total');
        $stats['total_fees'] = $ordersQuery->sum(DB::raw('total_tax + shipping_tax + shipping_total'));
        $stats['net_sales'] = $stats['total_sold'] - $stats['total_fees'];

        $lastMonth = Order::whereBetween(
            'date_created',
            [
                (new Carbon)->subMonth(1)->startOfMonth(),
                (new Carbon)->subMonth(1)->endOfMonth()
            ]
        )->whereIn('status', ['processing', 'completed']);



        // Compare total sold with last month.
        $lastMonthSold = $lastMonth->sum('total');

        if ($lastMonthSold > 0) {
            $comparission = ($stats['net_sales'] / $lastMonthSold) * 100;
            $comparission = $comparission > 100 ? $comparission - 100 : $comparission;
            $stats['percentage'] = $comparission;
        } else {
            $stats['percentage'] = 0;
        }

        // $stats['net_sales'] = sprintf( '$ %s', (float) ($stats['net_sales'] / 100) );

        // Compare orders created with last month
        $comparissionCount = ( $stats['total_orders'] / $lastMonth->count() ) * 100;
        $comparissionCount = $comparissionCount > 100
            ? $comparissionCount - 100
            : $comparissionCount;
        $stats['percentage_count'] = $comparissionCount;

        // Total orders to fulfill
        $ordersToFulfill = Order::orderBy('date_created', 'desc')
            ->where('status', 'processing');
        $stats['total_orders_to_fulfill'] = $ordersToFulfill->count();
        $stats['latest_orders'] = $ordersToFulfill->take(5);

        return $stats;
    }

    /**
     * Customer Stats
     *
     * @return array
     */
    protected function getCustomerStats(): array {
        $stats = [];

        $customerQuery = Customer::whereBetween(
            'date_created',
            [(new Carbon)->startOfMonth(), Carbon::now()]
        );

        $stats['total_customers'] = $customerQuery->count();

        return $stats;
    }
}
