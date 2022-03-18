<?php

namespace App\Http\Controllers;

use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Customer;
use Illuminate\Http\Request;
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
        $ordersQuery = Order::whereBetween(
            'date_created',
            [(new Carbon)->startOfMonth(), Carbon::now()]
        );
        $lastMonth = Order::whereBetween(
            'date_created',
            [
                (new Carbon)->subMonth(1)->startOfMonth(),
                (new Carbon)->subMonth(1)->endOfMonth()
            ]
        );

        $stats['total_orders'] = $ordersQuery->count();
        $stats['total_sold'] = $ordersQuery->sum('total');

        // Compare total sold with last month.
        $lastMonthSold = $lastMonth->sum('total');

        $comparission = ($stats['total_sold'] / $lastMonthSold) * 100;
        $comparission = $comparission > 100 ? $comparission - 100 : $comparission;
        $stats['percentage'] = $comparission;
        $stats['total_sold'] = sprintf('$ %s', $stats['total_sold'] / 100);

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
