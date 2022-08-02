<?php

namespace App\Services;

use App\Actions\Donations\AssignOrderDonationAction;
use App\Actions\Donations\AssignOrderDonationToCustomerAction;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Order;

class DonationsService
{
    /**
     * Calculate ORder Donations in generall
     *
     * @return void
     */
    public static function calculateOrderDonations(): void
    {
        QueryService::walkTrough(Order::query(), function ($order) {
            AssignOrderDonationAction::run($order);
        });
    }

    /**
     * Calculate all customer donations
     *
     * @return void
     */
    public static function calculateAllCustomerDonations(): void
    {
        QueryService::walkTrough(Customer::query(), function ($customer) {
            // Customer should has orders to calcualte donations
            if (! $customer->orders || $customer->orders->count() === 0) {
                return;
            }

            $customer->orders->map(function ($order) {
                AssignOrderDonationToCustomerAction::run($order);
            });
        });
    }
}
