<?php

namespace App\Actions\Donations;

use App\Models\Causes\Cause;
use App\Models\Causes\OrderDonation;
use App\Models\WooCommerce\Order;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;

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

        if (
            $order->getMetaValue('cause') &&
            null !== ($cause = Cause::findCause($order->getMetaValue('cause')))
        ) {
            $amount = $order->getMetaValue('1_donated_amount');
            $amount = is_null($amount) ? $order->getMetaValue('total_donated') : $amount;

            if (! is_null($amount) || ! empty($amount)) {
                OrderDonation::updateOrCreate([
                    'cause_id' => $cause->id,
                    'order_id' => $order->id,
                    'amount' => OrderDonation::valueToMoney($amount),
                    'donation_date' => $order->date_created,
                ]);

                Cache::tags('memberships')->flush();
            }
        }

        if (
            $order->getMetaValue('collab_for_causes') &&
            (int) $order->getMetaValue('collab_for_causes') > 0
        ) {
            $currentIndex = 0;
            $totalIndexes = (int) $order->getMetaValue('collab_for_causes');

            while ($currentIndex < $totalIndexes) {
                $causeId = $order->getMetaValue(sprintf('collab_for_causes_%s_cause', $currentIndex));
                $donation = $order->getMetaValue(sprintf('collab_for_causes_%s_donation_amount', $currentIndex));

                if ($causeId && $donation && null !== ($cause = Cause::findCause($causeId))) {
                    OrderDonation::updateOrCreate([
                        'cause_id' => $cause->id,
                        'order_id' => $order->id,
                        'amount' => OrderDonation::valueToMoney($donation),
                        'donation_date' => $order->date_created,
                    ]);
                }

                $currentIndex++;
            }

            Cache::tags('memberships')->flush();
        }
    }
}
