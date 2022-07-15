<?php

namespace Tests\Feature\Http\API;

use App\Actions\Printforia\MaybeCreatePrintforiaOrderAction;
use App\Actions\WooCommerce\Orders\SyncOrderLineItemProductsAction;
use App\Jobs\Pritnforia\MaybeCreatePrintforiaOrderJob;
use App\Models\Printforia\PrintforiaOrder;
use App\Models\User;
use App\Models\WooCommerce\Order;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\Feature\Http\BaseHttp;
use Illuminate\Support\Str;

class PrintforiaControllerTest extends BaseHttp
{
    public function test_printforia_get_webhook_values_should_returns_404_is_order_does_not_exists()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['create', 'read'],
        ]);

        $response = $this->withToken($token)
            ->post('/api/v1/printforia/webhook-values/sdfalsdflasdfasdf', [
                'status' => 'shipped'
            ]);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Order not found'
            ]);
    }

    public function test_printforia_get_webhook_values_should_requires_valid_status()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['create', 'read'],
        ]);

        $this->create_printforia_order();

        $printforiaOrder = PrintforiaOrder::wherePrintforiaOrderId('Bl2gAKAJuW9dPqiKxndwK')
            ->first();

        $response = $this->withToken($token)
            ->post(sprintf('/api/v1/printforia/webhook-values/%s', $printforiaOrder->printforia_order_id), [
                'status' => 'invalid_status'
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Invalid status. Please use shipped or approved'
            ]);

        $response = $this->withToken($token)
            ->post(sprintf('/api/v1/printforia/webhook-values/%s', $printforiaOrder->printforia_order_id), [
                'status' => 'shipped'
            ]);

        $response->assertStatus(200);
    }

    public function test_printforia_get_webhook_values_should_return_approved_data()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['create', 'read'],
        ]);

        $this->create_printforia_order();

        $printforiaOrder = PrintforiaOrder::wherePrintforiaOrderId('Bl2gAKAJuW9dPqiKxndwK')
            ->first();

        $response = $this->withToken($token)
            ->post(sprintf('/api/v1/printforia/webhook-values/%s', $printforiaOrder->printforia_order_id), [
                'status' => 'approved'
            ]);

        $data = [
            'type' => 'order_status_change',
            'status' => 'approved',
            'order_id' => $printforiaOrder->printforia_order_id,
            'customer_reference' => $printforiaOrder->customer_reference
        ];

        $payload = json_encode($data, JSON_UNESCAPED_SLASHES);
        $signature = hash_hmac('sha256', "1656985209.{$payload}", env('PRINTFORIA_API_KEY', 'foo'));
        $signature = sprintf('t=1656985209;s=%s', $signature);

        $response->assertStatus(200)
            ->assertJson([
                'signature' => $signature,
                'data' => $data
            ]);
    }

    protected function create_printforia_order()
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

        $order = Order::whereOrderId(550013)->first();

        SyncOrderLineItemProductsAction::run($order);
        MaybeCreatePrintforiaOrderAction::run($order);
    }

    protected function getPrintforiaUrl(string $endpoint): string
    {
        return sprintf(
            'https://api-sandbox.printforia.com/v2/%s', $endpoint
        );
    }
}
