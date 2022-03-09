<?php

namespace App\Jobs\Memberships;

use Exception;
use App\Models\Membership;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class RenewMembershipJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $membership_id = 0;
    protected bool $force = false;
    protected int $index = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $membership_id, bool $force = false, int $index = 1)
    {
        $this->membership_id = $membership_id;
        $this->force = $force;
        $this->index = $index;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $membership = Membership::find($this->membership_id);

            if (is_null($membership)) {
                throw new Exception("Membership Not Found");
            }

            if ( ! $this->force && $membership->daysUntilRenewal() > 0 ) {
                throw new Exception(
                    sprintf(
                        "Membership with ID #%s isn't expired.",
                        $membership->id
                    )
                );
            }

            // If the customer doesn't have a payment method. Cancell this
            // Renovation
            if (! $membership->customer->hasPaymentMethod()) {
                if ($membership->daysExpired() > 30) {
                    $membership->expire("Membership expired because was impossible find a payment method in 30 days.");
                } else {
                    $membership->sendPaymentNotFoundNotification($this->index);
                    $membership->status = Membership::IN_RENEWAL_STATUS;
                    $membership->logs()->create([
                        'description' => "Mebership renewal failed because we wasn't able to find a payment method for the customer."
                    ]);
                }

                $membership->shipping_status = 'N/A';
                $membership->save();

                throw new Exception("Customer does not have a payment method");
            }

            /**
             * Membership can renewal only if is active or in renewal status.
             *
             * Active means that currently is active (of course) and will
             * auto-renewal. This is the simple and normal flow.
             *
             * In-Renewal means that this isn't the first we're trying to renew
             * this membership. Maybe the renewal fail in the past because a
             * failed payment intent. So, this is the flow for members
             * with more that one intent.
             */
            if (! in_array($membership->status, [Membership::ACTIVE_STATUS, Membership::IN_RENEWAL_STATUS])) {
                throw new Exception('Membership must be active or in-renewal to be able to create another order');
            }

            // Initialize renewal intent.
            $membership->status = Membership::IN_RENEWAL_STATUS;
            $membership->shipping_status = 'N/A';

            try {
                $membership->customer->charge(
                    $membership->price ?? 3500,
                    $membership->customer->defaultPaymentMethod()->id,
                    ['description' => "Membership Renewal"]
                );

                $membership->status = Membership::AWAITING_PICK_GIFT_STATUS;
                $membership->shipping_status = Membership::SHIPPING_PENDING_STATUS;
                $membership->last_payment_intent = Carbon::now();
                $membership->end_at = $membership->end_at->addYear();
                $membership->payment_intents = 0;
                $membership->save();

                $order_status = 'kh-awm';
                $order_line_items = [
                    [
                        'product_id' => $membership->product_id,
                        'quantity' => 1
                    ]
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
                            'value' => 'yes'
                        ],
                        [
                            'key' => '_status',
                            'value' => 'kh-awm'
                        ],
                        [
                            'key' => '_membership_id',
                            'value' => $membership->id
                        ]
                    ]
                ];

                $wooService = \App\Services\WooCommerce\WooCommerceService::make();
                $order = $wooService->orders()->create(
                    new \App\Services\WooCommerce\DataObjects\Order($orderParams),
                    true
                );

                if ($order->id) {
                    $order->update([
                        'has_membership' => true,
                        'membership_id' => $membership->id
                    ]);
                    $membership->pending_order_id = $order->order_id;
                    $membership->save();
                }

                $membership->sendMembershipRenewedMail();

            } catch (\Laravel\Cashier\Exceptions\IncompletePayment $exception) {
                $membership->catchRenewalError($exception->payment->status);
            } catch (Exception $e) {
                $membership->catchRenewalError($e->getMessage());
            }

            $membership->save();
            return $membership;
        } catch ( Exception $e ) {
            $membership->logs()->create([
                'description' => sprintf("Renewal Error: %s", $e->getMessage())
            ]);

            return $e->getMessage();
        }
    }
}
