<?php

namespace App\Services;

use App\Models\Causes\Cause;
use App\Models\Causes\UserDonation;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Customer;
use Illuminate\Database\Eloquent\Collection;

class DonationsService
{
    /**
     * Calculate user Donations
     *
     * @param Customer $customer
     * @return Customer|Collection
     */
    public static function calculateCustomerDonations(Customer $customer): Customer|Collection
    {
        // Customer should has orders to calcualte donations
        if (! $customer->orders || $customer->orders->count() === 0) return $customer;

        // Reset calculations if customer has ones.
        if ($customer->donations && $customer->donations->count() > 0) {
            $customer->donations->map(function ($donation) {
                $donation->donation = 0;
                $donation->save();
            });
        }

        $customer->orders->map(function ($order) {
            DonationsService::addOrderDonationToCustomer($order);
        });

        return $customer->donations;
    }

    /**
     * Assign Order Donation to Customer
     *
     * @param Order $order
     * @return integer
     */
    public static function addOrderDonationToCustomer(Order $order): int
    {
        // Order needs to has a customer to calculate things.
        if (is_null($order->customer)) return 0;

        // Order must has Cause and Total Donated data. Otherwise it will return zero.
        if (! $order->getMetaValue('cause') || ! $order->getMetaValue('total_donated')) {
            return 0;
        }

        $cause = Cause::whereCauseId($order->getMetaValue('cause'))->first();

        // Cause must exists. Otherwise it will return zero.
        if (is_null($cause)) return 0;

        $donation = $order->customer->donations()
            ->whereCauseId($cause->id)
            ->first();

        if (is_null($donation)) {
            $donation = UserDonation::create([
                'customer_id' => $order->customer->id,
                'cause_id' => $cause->id,
                'donation' => 0
            ]);
        }

        $donation = $donation->addDonation($order->getMetaValue('total_donated'));

        return $donation->donation;
    }
}
