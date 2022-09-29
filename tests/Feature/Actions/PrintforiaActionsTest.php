<?php

namespace Tests\Feature\Actions;

use App\Actions\Printforia\CheckPrintforiaOrderAction;
use App\Actions\Printforia\MaybeCreatePrintforiaOrderAction;
use App\Actions\WooCommerce\Orders\SyncOrderLineItemProductsAction;
use App\Jobs\Pritnforia\MaybeCreatePrintforiaOrderJob;
use App\Mail\Printforia\OrderShipped;
use App\Models\Printforia\PrintforiaOrder;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Product;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class PrintforiaActionsTest extends BaseAction
{
    public function test_maybe_create_printforia_order_action_with_guest_customer(): void
    {
        $this->disableScout();

        Http::fake([
            $this->getUrl(endpoint: 'orders/550013') => Http::response(
                body: $this->fixture(name: 'WooCommerce/Printforia/OrderWithCustomer'),
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
            ),
        ]);

        Notification::fake();
        Bus::fake([
            MaybeCreatePrintforiaOrderJob::class,
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
    }

    public function test_maybe_create_printforia_order_action_with_customer()
    {
        $this->disableScout();

        Http::fake([
            $this->getUrl(endpoint: 'orders/550013') => function (Request $request) {
                if ($request->method() === 'GET') {
                    return Http::response(
                        body: $this->fixture(name: 'WooCommerce/Printforia/OrderWithCustomer'),
                        status: 200
                    );
                }

                if ($request->method() === 'PUT') {
                    return Http::response(
                        body: $this->fixture(name: 'WooCommerce/Printforia/OrderWithCustomerUpdated'),
                        status: 200
                    );
                }
            },
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
            $this->getUrl(endpoint: 'customers/2202') => Http::response(
                body: $this->fixture(name: 'WooCommerce/Printforia/Customer'),
                status: 200
            ),
            $this->getPrintforiaUrl(endpoint: 'orders') => Http::response(
                body: $this->fixture(name: 'Printforia/PrintforiaOrderWithCustomer'),
                status: 200
            ),
            $this->getPrintforiaUrl(endpoint: 'orders/Bl2gAKAJuW9dPqiKxndwK') => Http::response(
                body: $this->fixture(name: 'Printforia/PrintforiaOrderWithCustomer'),
                status: 200
            ),
        ]);

        Notification::fake();
        Bus::fake([
            MaybeCreatePrintforiaOrderJob::class,
        ]);

        $api = $this->get_woocommerce_service();
        $api->causes()->collectAndSync();
        $api->products()->getAndSync(id: 549943);
        $api->customers()->getAndSync(id: 2202);
        $api->paymentMethods()->getAndSync(id: 'kindhumans_stripe_gateway');
        $api->orders()->getAndSync(id: 550013);

        // Woo Product Must be a printforia product
        $product = Product::whereProductId(549943)->first();
        $this->assertTrue($product->is_printforia); // One bc it stores an integer in test

        $order = Order::whereOrderId(550013)->first();
        $customer = Customer::whereCustomerId(2202)->first();

        Bus::assertDispatched(MaybeCreatePrintforiaOrderJob::class);

        SyncOrderLineItemProductsAction::run($order);
        MaybeCreatePrintforiaOrderAction::run($order);

        $this->assertEquals(1, $order->items->count());
        $this->assertTrue(! is_null($order->printforiaOrder));
        $this->assertInstanceOf(PrintforiaOrder::class, $order->printforiaOrder);
        $this->assertEquals('Bl2gAKAJuW9dPqiKxndwK', $order->printforiaOrder->printforia_order_id);
        $this->assertEquals('order-550013', $order->printforiaOrder->customer_reference);
        $this->assertEquals(1, $order->printforiaOrder->items()->count());

        // Order must have a customer
        $this->assertInstanceOf(Customer::class, $order->customer);
        $this->assertEquals($customer->customer_id, $order->customer->customer_id);
        $this->assertEquals($customer->email, $order->customer->email);

        // Testing PRintforia Order
        $this->assertEquals(1, $order->printforiaOrder->items->count());
        $this->assertEquals(2, $order->printforiaOrder->items->first()->quantity);

        // Order should has the order printforia id
        $this->assertEquals('Bl2gAKAJuW9dPqiKxndwK', $order->getMetaValue('_printforia_order_id'));
    }

    public function test_check_printforia_order_action_should_update_order_only_if_status_is_not_completed(): void
    {
        $this->disableScout();

        Http::fake([
            $this->getUrl(endpoint: 'orders/550013') => function (Request $request) {
                if ($request->method() === 'GET') {
                    return Http::response(
                        body: $this->fixture(name: 'WooCommerce/Printforia/OrderWithCustomer'),
                        status: 200
                    );
                }

                if ($request->method() === 'PUT') {
                    return Http::response(
                        body: $this->fixture(name: 'WooCommerce/Printforia/OrderWithCustomerUpdated'),
                        status: 200
                    );
                }
            },
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
            $this->getUrl(endpoint: 'customers/2202') => Http::response(
                body: $this->fixture(name: 'WooCommerce/Printforia/Customer'),
                status: 200
            ),
            $this->getPrintforiaUrl(endpoint: 'orders') => Http::response(
                body: $this->fixture(name: 'Printforia/PrintforiaOrderWithCustomer'),
                status: 200
            ),
            $this->getPrintforiaUrl(endpoint: 'orders/Bl2gAKAJuW9dPqiKxndwK') => Http::sequence()
                ->push(
                    body: $this->fixture(name: 'Printforia/PrintforiaOrderWithCustomer'),
                    status: 200
                )->push(
                    body: $this->fixture(name: 'Printforia/PrintforiaOrderWithCustomerUpdated'),
                    status: 200
                ),
        ]);

        Notification::fake();
        Bus::fake([
            MaybeCreatePrintforiaOrderJob::class,
        ]);

        $api = $this->get_woocommerce_service();
        $api->causes()->collectAndSync();
        $api->products()->getAndSync(id: 549943);
        $api->customers()->getAndSync(id: 2202);
        $api->paymentMethods()->getAndSync(id: 'kindhumans_stripe_gateway');
        $api->orders()->getAndSync(id: 550013);

        // Woo Product Must be a printforia product
        $product = Product::whereProductId(549943)->first();
        $this->assertTrue($product->is_printforia); // One bc it stores an integer in test

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

        CheckPrintforiaOrderAction::run($order->printforiaOrder);

        $printforiaOrder = PrintforiaOrder::find($order->printforiaOrder->id);
        $order = Order::find($order->id);
        $this->assertEquals('completed', $printforiaOrder->status);
        $this->assertEquals('completed', $order->status);
    }

    public function test_create_or_update_printforia_order_should_change_to_in_progress(): void
    {
        $this->disableScout();

        Http::fake([
            $this->getUrl(endpoint: 'orders/550013') => function (Request $request) {
                if ($request->method() === 'GET') {
                    return Http::response(
                        body: $this->fixture(name: 'WooCommerce/Printforia/OrderWithCustomer'),
                        status: 200
                    );
                }

                if ($request->method() === 'PUT') {
                    return Http::response(
                        body: $this->fixture(name: 'WooCommerce/Printforia/OrderWithCustomerUpdated'),
                        status: 200
                    );
                }
            },
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
            $this->getUrl(endpoint: 'customers/2202') => Http::response(
                body: $this->fixture(name: 'WooCommerce/Printforia/Customer'),
                status: 200
            ),
            $this->getPrintforiaUrl(endpoint: 'orders') => Http::response(
                body: $this->fixture(name: 'Printforia/PrintforiaOrderWithCustomer'),
                status: 200
            ),
            $this->getPrintforiaUrl(endpoint: 'orders/Bl2gAKAJuW9dPqiKxndwK') => Http::sequence()
                ->push(
                    body: $this->fixture(name: 'Printforia/PrintforiaOrderWithCustomer'),
                    status: 200
                )->push(
                    body: $this->fixture(name: 'Printforia/OrderInProgress'),
                    status: 200
                ),
        ]);

        Notification::fake();
        Bus::fake([
            MaybeCreatePrintforiaOrderJob::class,
        ]);
        Mail::fake();

        $api = $this->get_woocommerce_service();
        $api->causes()->collectAndSync();
        $api->products()->getAndSync(id: 549943);
        $api->customers()->getAndSync(id: 2202);
        $api->paymentMethods()->getAndSync(id: 'kindhumans_stripe_gateway');
        $api->orders()->getAndSync(id: 550013);

        // Woo Product Must be a printforia product
        $product = Product::whereProductId(549943)->first();
        $this->assertTrue($product->is_printforia);

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

        CheckPrintforiaOrderAction::run($order->printforiaOrder);

        $printforiaOrder = PrintforiaOrder::wherePrintforiaOrderId($order->printforiaOrder->printforia_order_id)
            ->first();
        $this->assertEquals('in-progress', $printforiaOrder->status);
        $this->assertFalse($printforiaOrder->isProcessed());
        $this->assertFalse($printforiaOrder->email_sent);
        
        Mail::assertNotQueued(OrderShipped::class);
    }

    public function test_create_or_update_printforia_order_should_change_to_shipped(): void
    {
        $this->disableScout();

        Http::fake([
            $this->getUrl(endpoint: 'orders/550013') => function (Request $request) {
                if ($request->method() === 'GET') {
                    return Http::response(
                        body: $this->fixture(name: 'WooCommerce/Printforia/OrderWithCustomer'),
                        status: 200
                    );
                }

                if ($request->method() === 'PUT') {
                    return Http::response(
                        body: $this->fixture(name: 'WooCommerce/Printforia/OrderWithCustomerUpdated'),
                        status: 200
                    );
                }
            },
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
            $this->getUrl(endpoint: 'customers/2202') => Http::response(
                body: $this->fixture(name: 'WooCommerce/Printforia/Customer'),
                status: 200
            ),
            $this->getPrintforiaUrl(endpoint: 'orders') => Http::response(
                body: $this->fixture(name: 'Printforia/PrintforiaOrderWithCustomer'),
                status: 200
            ),
            $this->getPrintforiaUrl(endpoint: 'orders/Bl2gAKAJuW9dPqiKxndwK') => Http::sequence()
                ->push(
                    body: $this->fixture(name: 'Printforia/PrintforiaOrderWithCustomer'),
                    status: 200
                )->push(
                    body: $this->fixture(name: 'Printforia/OrderShipped'),
                    status: 200
                ),
        ]);

        Notification::fake();
        Bus::fake([
            MaybeCreatePrintforiaOrderJob::class,
        ]);
        Mail::fake();

        $api = $this->get_woocommerce_service();
        $api->causes()->collectAndSync();
        $api->products()->getAndSync(id: 549943);
        $api->customers()->getAndSync(id: 2202);
        $api->paymentMethods()->getAndSync(id: 'kindhumans_stripe_gateway');
        $api->orders()->getAndSync(id: 550013);

        // Woo Product Must be a printforia product
        $product = Product::whereProductId(549943)->first();
        $this->assertTrue($product->is_printforia);

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

        CheckPrintforiaOrderAction::run($order->printforiaOrder);

        $printforiaOrder = PrintforiaOrder::wherePrintforiaOrderId($order->printforiaOrder->printforia_order_id)
            ->first();
        $this->assertEquals('shipped', $printforiaOrder->status);
        $this->assertTrue($printforiaOrder->isProcessed());
        $this->assertTrue($printforiaOrder->email_sent);
        
        Mail::assertQueued(OrderShipped::class);
    }

    protected function getPrintforiaUrl(string $endpoint): string
    {
        return sprintf(
            'https://api-sandbox.printforia.com/v2/%s', $endpoint
        );
    }
}
