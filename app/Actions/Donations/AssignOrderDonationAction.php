<?php

namespace App\Actions\Donations;

use App\Models\Causes\Cause;
use App\Models\Causes\OrderDonation;
use App\Models\WooCommerce\Order;
use Cknow\Money\Money;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class AssignOrderDonationAction
{
    use AsAction;

    /**
     * Handle the order donation
     *
     * @param  int|string  $orderId
     * @return void
     */
    public function handle(int|string|Order $orderId)
    {
        if ($orderId instanceof Order) {
            $orderId = $orderId->id;
        }

        $order = Order::find($orderId);
        $donationsCacheKey = sprintf('order_donations_for_%s', $order->id);
        $orderDetailCacheKey = sprintf('woocommerce_order_%s', $order->id);

        if (
            $order->getMetaValue('cause') &&
            null !== ($cause = Cause::findCause($order->getMetaValue('cause')))
        ) {
            try {
                $amount = Money::sum(
                    $order->getBucketCauseDonation(),
                    $order->getMembershipDonation()
                );

                if ($amount->isPositive()) {
                    OrderDonation::updateOrCreate([
                        'cause_id' => $cause->id,
                        'order_id' => $order->id,
                        'amount' => (int) $amount->getAmount(),
                        'donation_date' => $order->date_created,
                    ]);

                    Cache::tags('memberships')->flush();
                    Cache::tags('orders')->forget($donationsCacheKey);
                    Cache::tags('orders')->forget($orderDetailCacheKey);
                }
            } catch (Throwable $e) {
                $payload = [
                    'bucket' => $order->getMetaValue('1_donated_amount'),
                    'membership' => $order->getMetaValue('kindness_donated_amount')
                ];

                Log::error(
                    sprintf(
                        'Was impossible to calculate donations to order #%s because %s. Payload: %s',
                        $order->order_id,
                        $e->getMessage(),
                        json_encode($payload)
                    )
                );
            }
        }

        if (
            $order->getMetaValue('collab_for_causes') &&
            (int) $order->getMetaValue('collab_for_causes') > 0
        ) {
            $currentIndex = 0;
            $totalIndexes = (int) $order->getMetaValue('collab_for_causes');

            while ($currentIndex < $totalIndexes) {
                try {
                    $causeId = $order->getMetaValue(sprintf('collab_for_causes_%s_cause', $currentIndex));
                    $donation = $order->getMetaValue(sprintf('collab_for_causes_%s_donation_amount', $currentIndex));

                    if ($causeId && $donation && null !== ($cause = Cause::findCause($causeId))) {
                        $donation = $donation < 0 ? 0 : $donation;
                        $donation = Money::USD($donation, ! is_float($donation));
                        OrderDonation::updateOrCreate([
                            'cause_id' => $cause->id,
                            'order_id' => $order->id,
                            'amount' => (int) $donation->getAmount(),
                            'donation_date' => $order->date_created,
                        ]);
                    }
                } catch (Throwable $e) {
                    Log::error(
                        sprintf(
                            'Was impossible to calculate donations to order #%s because: <code>%s.</code> Payload: %s',
                            $order->order_id,
                            $e->getMessage(),
                            $order->getMetaValue(sprintf('collab_for_causes_%s_donation_amount', $currentIndex))
                        )
                    );
                }

                $currentIndex++;
            }

            Cache::tags('memberships')->flush();
            Cache::tags('orders')->forget($donationsCacheKey);
            Cache::tags('orders')->forget($orderDetailCacheKey);
        }
    }
}
