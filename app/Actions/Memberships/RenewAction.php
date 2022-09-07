<?php

namespace App\Actions\Memberships;

use App\Models\Membership;
use App\Models\WooCommerce\Order;
use Carbon\Carbon;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class RenewAction
{
    use AsAction;

    /**
     * Renew a single membership
     *
     * @return Membership|string
     */
    public function handle(
        Membership $membership, bool $force = false
    ): Membership|string
    {
        try {
            /**
             * Unless the renew being forced, the membership should have zero
             * days until renewal date. Otherwise, this membership isn't
             * ready to renew.
             */
            if (! $force && $membership->daysUntilRenewal() > 0) {
                throw new Exception(
                    sprintf(
                        "Membership with ID #%s isn't expired.",
                        $membership->id
                    )
                );
            }

            /**
             * Membership can renewal only if is active or in renewal status.
             *
             * Active means that currently is active (of course) and will
             * auto-renewal. This is the simple and normal flow.
             *
             * In-Renewal means that this isn't the first time we're trying to
             * renew this membership. Maybe the renewal fail in the past because a
             * failed payment intent. So, this is the flow for members
             * with more that one intent.
             */
            if (
                ! in_array(
                    $membership->status,
                    [Membership::ACTIVE_STATUS, Membership::IN_RENEWAL_STATUS]
                )
            ) {
                throw new Exception(
                    'Membership must be active or in-renewal to be able to create another order'
                );
            }

            /**
             * If the customer does not have a payment method attached,
             * the renewal won't be possible.
             *
             * If the membership just expired, it will change to "IN-RENEWAL"
             * status and this will notifiy to customer through an email that
             * needs to attach a payment method to renew the membership.
             *
             * If this renewal is happening after more than one intent, it's
             * possible that the membership has more than 30 days expired.
             * IF this is true, the membership will be expired. status should be
             * "EXPIRED"
             */
            if (! $membership->customer->hasPaymentMethod()) {
                if ($membership->daysExpired() > 30) {
                    $errorMessage = 'Membership expired because was impossible find a payment method in 30 days.';
                    $membership->expire($errorMessage);
                } else {
                    $membership->sendPaymentNotFoundNotification();
                    $membership->status = Membership::IN_RENEWAL_STATUS;
                    $membership->shipping_status = 'N/A';
                    $membership->save();
                    $errorMessage = "Mebership renewal failed because we wasn't able to find a payment method for the customer";
                    $membership->logs()->create([
                        'description' => $errorMessage,
                    ]);
                }

                throw new Exception($errorMessage);
            }

            /**
             * Initialize the renewal.
             * Inmediatally must be "In-Renewal" and Shipping Status in "N/A"
             */
            $membership->status = Membership::IN_RENEWAL_STATUS;
            $membership->shipping_status = 'N/A';

            /**
             * Make the payment intent.
             *
             * If this fails, automatically should throws an expection, so
             * the code stops here in that case.
             */
            $membership->customer->charge(
                $membership->price ?? 3500,
                $membership->customer->defaultPaymentMethod()->id,
                ['description' => 'Membership Renewal']
            );

            /**
             * If the payment was processed. The membership status should change
             * to "Awaiting Pick Gift".
             *
             * Why "Awaiting Pick Gift"?
             *
             * Because Kindhumans gives a product each time a customer buys
             * a membership. In this case is the same, when membership has been
             * renewed, we need to give a product to the customer. So, we
             * are going to send an email to the customer with instructions to
             * pick the gift product. For now, we are going to leave the
             * membership as "Awaiting Pick Gift Status". It will change to
             * Active when user has picked it.
            */
            $membership->status = Membership::AWAITING_PICK_GIFT_STATUS;
            $membership->shipping_status = 'N/A';
            $membership->last_payment_intent = Carbon::now();
            $membership->end_at = $membership->end_at->addYear();
            $membership->payment_intents = 0;
            $membership->save();

            /**
             * We are going to create the order in WooCommerce with this status
             * "kh-awm" which is the same than "Kindhumans Awaiting membership".
             * This is a WooCOmmerce Order Status.
             */
            $order_status = 'kh-awm';
            $order_line_items = [
                [
                    'product_id' => $membership->product_id,
                    'quantity' => 1,
                ],
            ];

            $orderParams = [
                'payment_method' => 'kindhumans_stripe_gateway',
                'payment_method_title' => 'Credit Card',
                'customer_id' => $membership->customer->customer_id,
                'set_paid' => true,
                'date_completed' => Carbon::now(),
                'date_paid' => Carbon::now(),
                'status' => $order_status,
                'billing' => (array) $membership->customer->billing,
                'shipping' => (array) $membership->customer->shipping,
                'line_items' => $order_line_items,
                'total' => $membership->price,
                'meta_data' => [
                    [
                        'key' => '_created_from_kinja_api',
                        'value' => 'yes',
                    ],
                    [
                        'key' => '_status',
                        'value' => 'kh-awm',
                    ],
                    [
                        'key' => '_membership_id',
                        'value' => $membership->id,
                    ],
                    [
                        'key' => '_order_synced_with_kinja_admin',
                        'value' => 'yes'
                    ]
                ],
            ];

            $order = Order::api()->create(
                new \App\Services\WooCommerce\DataObjects\Order($orderParams),
                true
            );

            if ($order->id) {
                $order->update([
                    'has_membership' => true,
                    'membership_id' => $membership->id,
                ]);
                $membership->pending_order_id = $order->order_id;
            }

            $membership->sendMembershipRenewedMail();
            $membership->save();

            return $membership;
        } catch (Throwable $e) {
            $membership->logs()->create([
                'description' => sprintf('Renewal Error: %s', $e->getMessage()),
            ]);

            return $e->getMessage();
        }
    }
}
