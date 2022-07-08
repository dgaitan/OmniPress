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
     * @param  int|string  $orderId
     * @return void
     */
    public function handle(int|string $orderId): void
    {
        $order = Order::find($orderId);

        // Order needs to has a customer to calculate things.
        if (is_null($order->customer)) {
            return;
        }

        if ($order->donations->isEmpty()) {
            return;
        }

        $order->donations->map(function ($donation) use ($order) {
            $userDonation = UserDonation::whereCauseId($donation->cause->id)
                ->whereCustomerId($order->customer->id)
                ->first();

            if (is_null($userDonation)) {
                $userDonation = UserDonation::create([
                    'customer_id' => $order->customer->id,
                    'cause_id' => $donation->cause->id,
                    'donation' => 0,
                ]);
            }

            $userDonation->addDonation($donation->amount);
        });
    }
}
