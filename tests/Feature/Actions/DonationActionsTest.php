<?php

namespace Tests\Feature\Actions;

use App\Models\Causes\Cause;
use App\Models\Causes\OrderDonation;
use App\Models\WooCommerce\Order;
use App\Services\WooCommerce\WooCommerceService;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Actions\BaseAction;

class DonationActionsTest extends BaseAction
{
    public function test_assign_order_donations(): void
    {
        Http::fake([
            $this->getUrl(endpoint: 'orders/418136') => Http::response(
                body: $this->fixture('WooCommerce/OrderDonationCause'),
                status: 200
            ),
            $this->getUrl(endpoint: 'customers/2064') => Http::response(
                body: $this->fixture('WooCommerce/CustomerDetail'),
                status: 200
            ),
            $this->getUrl(endpoint: 'payment_gateways/kindhumans_stripe_gateway') => Http::response(
                body: $this->fixture('WooCommerce/PaymentMethodDetail'),
                status: 200
            ),
            $this->getUrl(endpoint: 'causes*') => Http::response(
                body: $this->fixture('causesList'),
                status: 200
            )
        ]);

        Notification::fake();

        $api = WooCommerceService::make();
        $api->causes()->collectAndSync();
        $api->paymentMethods()->getAndSync('kindhumans_stripe_gateway');
        $api->customers()->getAndSync(2064);
        $api->orders()->getAndSync(418136);

        $cause = Cause::whereCauseId(581)->first();
        $order = Order::whereOrderId(418136)->first();

        $this->assertEquals($cause, $order->getCause());
        $this->assertEquals(123, $order->donations->sum('amount'));
        $this->assertEquals('$1.23', $order->totalDonated()->formated());
        $this->assertInstanceOf(OrderDonation::class, $order->donations->first());
        $this->assertEquals(1, $order->donations->count());

        $donations = $order->donations->map(function ($donation) {
            return [
                'id' => $donation->id,
                'cause' => [
                    'id' => $donation->cause->id,
                    'name' => $donation->cause->name,
                    'type' => $donation->cause->getCauseType(),
                ],
                'amount' => [
                    'value' => $donation->amount,
                    'format' => $donation->getMoneyValue('amount')->format(),
                ],
            ];
        });

        $this->assertEquals($donations, $order->getDonations());
    }
}
