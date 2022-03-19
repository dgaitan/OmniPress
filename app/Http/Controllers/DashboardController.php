<?php

namespace App\Http\Controllers;

use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Customer;
use App\Analytics\OrderAnalytics;
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

        $orderAnalytics = new OrderAnalytics(
            OrderAnalytics::CURRENT_MONTH_RANGE,
            OrderAnalytics::PREVIOUS_PERIOD
        );

        $stats = [
            'net_sales' => $orderAnalytics->getNetSales(),
            'total_orders' => $orderAnalytics->getTotalOrders(),
            'percentage' => $orderAnalytics->getSalesPercentageDifference(),
            'percentage_count' => $orderAnalytics->getTotalOrdersPercentageDifference()
        ];

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
