<?php

namespace App\Actions\Donations;

use App\Models\Causes\UserDonation;
use App\Models\WooCommerce\Order;
use Lorisleiva\Actions\Concerns\AsAction;

class AssignOrderDonationToCustomerAction
{
    use AsAction;

    /**
     * Handling the order doantion for a customer
     *
     * @param  int|string|Order  $orderId
     * @return void
     */
    public function handle(int|string|Order $orderId): void
    {
        if ($orderId instanceof Order) {
            $orderId = $orderId->id;
        }

        $order = Order::find($orderId);

        // Order needs to has a customer to calculate things.
        if (is_null($order->customer)) {
            return;
        }

        if ($order->donations->isEmpty()) {
            return;
        }

        $order->donations->map(function ($donation) use ($order) {
            UserDonation::updateOrCreate([
                'cause_id' => $donation->cause->id,
                'customer_id' => $order->customer->id,
                'donation' => $donation->amount,
                'donation_date' => $order->date_created
            ]);
        });
    }
}
