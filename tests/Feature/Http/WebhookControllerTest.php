<?php

namespace Tests\Feature\Http;

use App\Actions\Printforia\MaybeCreatePrintforiaOrderAction;
use App\Actions\WooCommerce\Orders\SyncOrderLineItemProductsAction;
use App\Jobs\Pritnforia\MaybeCreatePrintforiaOrderJob;
use App\Mail\Printforia\OrderShipped;
use App\Models\WooCommerce\Order;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\Utils\InteractsWithScout;

class WebhookControllerTest extends BaseHttp
{
    use InteractsWithScout;

    public function test_printforia_webhook_should_return_ok(): void
    {
        $data = [
            'status' => 'feliz',
            'type' => 'order_status_change',
        ];

        $payload = json_encode($data, JSON_UNESCAPED_SLASHES);
        $signature = hash_hmac('sha256', "1656985209.{$payload}", env('PRINTFORIA_API_KEY', 'foo'));
        $signature = sprintf('t=1656985209;s=%s', $signature);

        $response = $this->withHeaders([
            'X-Signature' => $signature,
        ])->post('/webhooks/printforia', $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Webhook Processed',
            ]);
    }

    public function test_printforia_webhook_should_return_error_by_missing_signature(): void
    {
        $data = [
            'status' => 'feliz',
            'type' => 'order_status_change',
        ];

        $response = $this->post('/webhooks/printforia', $data);

        $response->assertStatus(403)
            ->assertJson([
                'error' => 'Siganture is not present in request.',
            ]);
    }

    public function test_printforia_webhook_should_return_error_by_missing_status_in_request(): void
    {
        $data = [
            'type' => 'order_status_change',
        ];

        $payload = json_encode($data, JSON_UNESCAPED_SLASHES);
        $signature = hash_hmac('sha256', "1656985209.{$payload}", env('PRINTFORIA_API_KEY', 'foo'));
        $signature = sprintf('t=1656985209;s=%s', $signature);

        $response = $this->withHeaders([
            'X-Signature' => $signature,
        ])->post('/webhooks/printforia', $data);

        $response->assertStatus(403)
            ->assertJson([
                'error' => 'Status is not present in response',
            ]);
    }

    public function test_printforia_order_should_change_to_shipped_after_webhook_request(): void
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

        Mail::fake();
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

        Bus::assertDispatched(MaybeCreatePrintforiaOrderJob::class);

        $data = [
            'status' => 'shipped',
            'type' => 'order_status_change',
            'order_id' => 'Bl2gAKAJuW9dPqiKxndwK',
            'customer_reference' => 'order-550013',
            'carrier' => 'USPS',
            'tracking_number' => '12345678901234567890',
            'tracking_url' => 'https://tracking.example.com/12345678901234567890',
        ];

        $order = Order::whereOrderId(550013)->first();

        SyncOrderLineItemProductsAction::run($order);
        MaybeCreatePrintforiaOrderAction::run($order);

        $payload = json_encode($data, JSON_UNESCAPED_SLASHES);
        $signature = hash_hmac('sha256', "1656985209.{$payload}", env('PRINTFORIA_API_KEY', 'foo'));
        $signature = sprintf('t=1656985209;s=%s', $signature);

        $response = $this->withHeaders([
            'X-Signature' => $signature,
        ])->post('/webhooks/printforia', $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Webhook Processed',
            ]);

        Mail::assertQueued(OrderShipped::class);

        $order = Order::whereOrderId(550013)->first();

        $this->assertEquals('completed', $order->status);
        $this->assertEquals('shipped', $order->printforiaOrder->status);
        $this->assertEquals('USPS', $order->printforiaOrder->carrier);
        $this->assertEquals('12345678901234567890', $order->printforiaOrder->tracking_number);
        $this->assertEquals('https://tracking.example.com/12345678901234567890', $order->printforiaOrder->tracking_url);
    }

    protected function getPrintforiaUrl(string $endpoint): string
    {
        return sprintf(
            'https://api-sandbox.printforia.com/v2/%s', $endpoint
        );
    }
}
