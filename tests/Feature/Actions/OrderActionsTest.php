<?php

namespace Tests\Feature\Actions;

use App\Actions\WooCommerce\Orders\UpdateOrderAction;
use App\Models\WooCommerce\Order;
use App\Services\WooCommerce\DataObjects\Order as OrderDataObject;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Tests\Feature\Http\BaseHttp;

class OrderActionsTest extends BaseHttp
{
    public function test_order_update_action_should_return_null(): void
    {
        Http::fake([
            $this->getUrl(endpoint: 'orders/*') => Http::response(
                body: ['foo' => 'bar'],
                status: 404
            ),
        ]);

        $order = UpdateOrderAction::run(111, ['status' => 'ok'], true);

        $this->assertTrue(is_null($order));
    }

    public function test_order_update_action_should_return_model_updated(): void
    {
        Http::fake([
            $this->getUrl(endpoint: 'orders/550019') => function (Request $request) {
                if ($request->method() == 'GET') {
                    return Http::response(
                        body: $this->fixture('WooCommerce/OrderGetResponse'),
                        status: 200
                    );
                }

                if ($request->method() == 'PUT') {
                    return Http::response(
                        body: $this->fixture('WooCommerce/OrderPutResponse'),
                        status: 200
                    );
                }
            },
        ]);

        $api = $this->get_woocommerce_service();

        Notification::fake();
        Bus::fake();

        $order = $api->orders()->getAndSync(550019);

        // Let's inspect the current order before update it using action
        $this->assertTrue(is_null($order->getMetaValue('mi_new_key_from_api')));
        $this->assertEquals('processing', $order->status);

        // Now let's update the order using the action
        $order = UpdateOrderAction::run(550019, [
            'status' => 'completed',
            'meta_data' => [
                [
                    'key' => 'mi_new_key_from_api',
                    'value' => 'my_new_value_from_api',
                ],
            ],
        ], true);

        // $order var must be an instance of order now
        $this->assertInstanceOf(Order::class, $order);
        $this->assertTrue(! is_null($order->getMetaValue('mi_new_key_from_api')));
        $this->assertEquals('my_new_value_from_api', $order->getMetaValue('mi_new_key_from_api'));
        $this->assertEquals('completed', $order->status);
    }
}
