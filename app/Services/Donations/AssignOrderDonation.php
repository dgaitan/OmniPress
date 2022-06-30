<?php

namespace App\Services\Donations;

use App\Models\Causes\Cause;
use App\Models\Causes\OrderDonation;
use App\Models\WooCommerce\Order;
use App\Services\BaseService;

class AssignOrderDonation extends BaseService
{
    public function __construct(public $order_id)
    {}

    /**
     * Rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'integer']
        ];
    }

    public function handle()
    {
        $order = Order::find($this->order_id);

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
                    'amount' => OrderDonation::valueToMoney($amount)
                ]);
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
                        'amount' => OrderDonation::valueToMoney($donation)
                    ]);
                }

                $currentIndex++;
            }
        }

    }
}
