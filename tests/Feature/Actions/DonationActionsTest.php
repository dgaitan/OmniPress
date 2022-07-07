<?php

namespace Tests\Feature\Actions;

use App\Jobs\Donations\AssignOrderDonationJob;
use App\Models\Causes\Cause;
use App\Models\WooCommerce\Order;
use App\Services\WooCommerce\WooCommerceService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Actions\BaseAction;

class DonationActionsTest extends BaseAction
{
    public function test_assign_order_donations(): void
    {
        Http::fake([
            $this->getUrl(endpoint: 'orders/408177') => Http::response(
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

        Bus::fake([
            AssignOrderDonationJob::class
        ]);
        Notification::fake();

        $api = WooCommerceService::make();
        $api->causes()->collectAndSync();
        $api->paymentMethods()->getAndSync('kindhumans_stripe_gateway');
        $api->customers()->getAndSync(2064);
        $api->orders()->getAndSync(408177);

        Bus::assertDispatched(AssignOrderDonationJob::class);

        $cause = Cause::whereCauseId(102529)->first();
        $order = Order::whereOrderId(408177)->first();

        $this->assertEquals($cause, $order->getCause());
        $this->assertEquals();
    }
}