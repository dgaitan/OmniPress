<?php

namespace App\Services;

use App\Models\CauseDonation;
use App\Models\Causes\Cause;
use App\Models\Causes\UserDonation;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Customer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class DonationsService
{
    /**
     * CauseDonation is a model that collects the total donated for a cause
     * and a month period. This function basically add donations amount to
     * a Cause Donation Period.
     *
     * @param Order $order
     * @return Order
     */
    public static function addCauseDonation(Order $order): Order
    {
        // Order must has Cause and Total Donated data. Otherwise it will return zero.
        if (! $order->getMetaValue('cause') || ! $order->getMetaValue('total_donated')) {
            return $order;
        }

        $cause = Cause::whereCauseId($order->getMetavalue('cause'))->first();

        if (is_null($cause)) return $order;

        $causeDonationPeriod = DonationsService::getOrCreateDonationPeriod(
            cause: $cause, date: $order->date_created
        );
        $causeDonationPeriod->addDonation(
            amount: $order->getMetaValue('total_donated')
        );

        return $order;
    }

    /**
     * Get the current donation period
     *
     * @param Cause $cause
     * @return CauseDonation
     */
    public static function getCurrentDonationPeriod(Cause $cause): CauseDonation
    {
        return DonationsService::getOrCreateDonationPeriod($cause, now());
    }

    /**
     * Get Total Cause Donation
     *
     * @param Cause $cause
     * @param boolean $lifetime
     * @param Carbon|null|null $from
     * @param Carbon|null|null $to
     * @return float
     */
    public static function getCauseFieldTotal(
        Cause $cause,
        string $field = 'amount',
        bool $lifetime = true,
        Carbon|null $from = null,
        Carbon|null $to = null
    ): int
    {
        if (! in_array($field, ['amount', 'total_orders'])) {
            throw new InvalidArgumentException(
                'Invalid cause field to calculate a total. Please be sure of be using: amount or total_orders'
            );
        }
        
        $total = 0;
        
        if ($lifetime) {
            $total = $cause->donations()->sum($field);
        } else {
            $total = $cause->donations()
                ->where('from', DonationsService::getDateString($from))
                ->where('to', DonationsService::getDateString($to))
                ->sum($field);
        }

        return (int) $total;
    }

    /**
     * Get or Create a donation period based on a date
     *
     * @param Cause $cause
     * @param Carbon|null|null $date
     * @return CauseDonation
     */
    public static function getOrCreateDonationPeriod(
        Cause $cause,
        Carbon|null $date = null,
    ): CauseDonation {
        $donationPeriod = DonationsService::getDonationPeriod(
            cause: $cause, date: $date
        );

        if (is_null($donationPeriod)) {
            $donationPeriod = DonationsService::createDonationPeriod(
                cause: $cause, date: $date
            );
        }

        return $donationPeriod;
    }

    /**
     * Create a donation period
     *
     * @param Cause $cause
     * @param Carbon|null|null $date
     * @return CauseDonation
     */
    public static function createDonationPeriod(
        Cause $cause, Carbon|null $date = null
    ): CauseDonation
    {
        $date = is_null($date) ? now() : $date;

        return CauseDonation::create([
            'cause_id' => $cause->id,
            'from' => $date->startOfMonth()->toDateString(),
            'to' => $date->endOfMonth()->toDateString(),
            'amount' => 0,
            'total_orders' => 0
        ]);
    }
    
    /**
     * Get a donation period for a giving date.
     *
     * @param Cause $cause
     * @param Carbon|null|null $date
     * @return CauseDonation|null
     */
    public static function getDonationPeriod(
        Cause $cause,
        Carbon|null $date = null
    ): CauseDonation|null
    {
        $date = is_null($date) ? now() : $date;

        return CauseDonation::where('cause_id', $cause->id)
            ->where('from', $date->startOfMonth()->toDateString())
            ->where('to', $date->endOfMonth()->toDateString())
            ->first();
    }
    
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

    /**
     * Get date string of a giving date.
     *
     * @param Carbon|null|null $date
     * @return Carbon
     */
    public static function getDateString(
        Carbon|null $date = null
    ): string
    {
        $date = is_null($date) ? now() : $date;

        return $date->toDateString();
    }
}
