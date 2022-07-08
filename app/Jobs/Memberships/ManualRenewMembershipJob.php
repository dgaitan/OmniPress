<?php

namespace App\Jobs\Memberships;

use App\Models\Membership;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ManualRenewMembershipJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $membership_id = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $membership_id)
    {
        $this->membership_id = $membership_id;
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
                throw new Exception('Membership Not Found');
            }

            $membership->status = Membership::AWAITING_PICK_GIFT_STATUS;
            $membership->shipping_status = 'N/A';
            $membership->last_payment_intent = Carbon::now();
            $membership->end_at = $membership->end_at->addYear();
            $membership->payment_intents = 0;
            $membership->save();

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
                ],
            ];

            $wooService = \App\Services\WooCommerce\WooCommerceService::make();
            $order = $wooService->orders()->create(
                new \App\Services\WooCommerce\DataObjects\Order($orderParams),
                true
            );

            if ($order->id) {
                $order->update([
                    'has_membership' => true,
                    'membership_id' => $membership->id,
                ]);
                $membership->pending_order_id = $order->order_id;
                $membership->save();
            }

            $membership->sendMembershipRenewedMail();

            $membership->save();

            return $membership;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
