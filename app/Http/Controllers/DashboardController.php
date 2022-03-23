<?php

namespace App\Http\Controllers;

use App\Models\WooCommerce\Order;
use App\Analytics\OrderAnalytics;
use App\Analytics\CustomerAnalytics;
use App\Http\Resources\OrderCollection;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        $stats['latest_orders'] = new OrderCollection($ordersToFulfill->take(5)->get());

        return $stats;
    }

    /**
     * Customer Stats
     *
     * @return array
     */
    protected function getCustomerStats(): array {
        $stats = [];

        $customerAnalytics = new CustomerAnalytics(
            CustomerAnalytics::CURRENT_MONTH_RANGE,
            CustomerAnalytics::PREVIOUS_PERIOD
        );

        $stats['total_customers'] = $customerAnalytics->getTotalCustomers();
        $stats['percentage'] = $customerAnalytics->getTotalPercentageDifference();

        return $stats;
    }
}
