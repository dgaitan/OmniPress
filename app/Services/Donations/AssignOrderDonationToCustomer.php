<?php

namespace App\Services\Donations;

use App\Models\Causes\UserDonation;
use App\Models\WooCommerce\Order;
use App\Services\BaseService;

class AssignOrderDonationToCustomer extends BaseService
{
    public function __construct(public $order_id)
    {
    }

    /**
     * Rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'integer'],
        ];
    }

    /**
     * Handle the service
     *
     * @return void
     */
    public function handle()
    {
        $order = Order::find($this->order_id);

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
