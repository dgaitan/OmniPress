<?php

namespace Tests\Feature\Actions;

use App\Actions\Printforia\MaybeCreatePrintforiaOrderAction;
use App\Actions\WooCommerce\Orders\SyncOrderLineItemProductsAction;
use App\Jobs\Pritnforia\MaybeCreatePrintforiaOrderJob;
use App\Jobs\WooCommerce\Orders\SyncOrderLineItemProductsJob;
use App\Models\Printforia\PrintforiaOrder;
use App\Models\WooCommerce\Order;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;

class PrintforiaActionsTest extends BaseAction
{
    public function test_maybe_create_printforia_order_action_with_guest_customer(): void
    {
        $this->disableScout();

        Http::fake([
            $this->getUrl(endpoint: 'orders/550013') => Http::response(
                body: $this->fixture(name: 'WooCommerce/Printforia/OrderWithGuestCustomer'),
                status: 200
            ),
            $this->getUrl(endpoint: 'payment_gateways/kindhumans_stripe_gateway') => Http::response(
                body: $this->fixture(name: 'WooCommerce/PaymentMethodDetail'),
                status: 200
            ),
            $this->getUrl(endpoint: 'causes*') => Http::response(
                body: $this->fixture(name: 'causesList'),
                status: 200
            ),
            $this->getUrl(endpoint: 'products/549943') => Http::response(
                body: $this->fixture(name: 'WooCommerce/Printforia/PrintforiaProduct'),
                status: 200
            ),
            $this->getPrintforiaUrl(endpoint: 'orders') => Http::response(
                body: $this->fixture(name: 'printforiaOrder'),
                status: 200
            ),
            $this->getPrintforiaUrl(endpoint: 'orders/Bl2gAKAJuW9dPqiKxndwK') => Http::response(
                body: $this->fixture(name: 'printforiaOrder'),
                status: 200
            )
        ]);

        Notification::fake();
        Bus::fake([
            MaybeCreatePrintforiaOrderJob::class
        ]);

        $api = $this->get_woocommerce_service();
        $api->causes()->collectAndSync();
        $api->products()->getAndSync(549943);
        $api->paymentMethods()->getAndSync(id: 'kindhumans_stripe_gateway');
        $api->orders()->getAndSync(id: 550013);

        $order = Order::whereOrderId(550013)->first();

        Bus::assertDispatched(MaybeCreatePrintforiaOrderJob::class);

        SyncOrderLineItemProductsAction::run($order);
        MaybeCreatePrintforiaOrderAction::run($order);

        $this->assertEquals(1, $order->items->count());
        $this->assertTrue(! is_null($order->printforiaOrder));
        $this->assertInstanceOf(PrintforiaOrder::class, $order->printforiaOrder);
        $this->assertEquals('Bl2gAKAJuW9dPqiKxndwK', $order->printforiaOrder->printforia_order_id);
        $this->assertEquals('order-550013', $order->printforiaOrder->customer_reference);
        $this->assertEquals(1, $order->printforiaOrder->items()->count());
        // MaybeCreatePrintforiaOrderAction::assertPushed();
    }

    protected function getPrintforiaUrl(string $endpoint): string
    {
        return sprintf(
            'https://api-sandbox.printforia.com/v2/%s', $endpoint
        );
    }
}
