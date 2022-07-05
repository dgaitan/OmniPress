<?php

namespace Tests\Feature\Services\WooCommerce;

use App\Models\WooCommerce\Order as WooCommerceOrder;
use App\Services\WooCommerce\DataObjects\Order;
use App\Services\WooCommerce\Resources\OrderResource;
use App\Services\WooCommerce\WooCommerceService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Http\BaseHttp;

class WooCommerceOrderResourceTest extends BaseHttp
{
    use RefreshDatabase;

    protected $apiUrl = 'http://host.docker.internal:10003/wp-json/wc/v3/';

    public function test_order_resource_instance()
    {
        $api = WooCommerceService::make();

        $this->assertInstanceOf(OrderResource::class, $api->orders());
        $this->assertClassHasAttribute('endpoint', OrderResource::class);
        $this->assertClassHasAttribute('factory', OrderResource::class);

        $this->assertInstanceOf(WooCommerceService::class, $api->orders()->service());
    }

    public function test_getting_order_using_order_resource()
    {
        $api = WooCommerceService::make();

        Http::fake([
            'http://host.docker.internal:10003/wp-json/wc/v3/orders/727' => Http::response(
                body: $this->fixture('WooCommerce/OrderDetail'),
                status: 200
            ),
        ]);

        $order = $api->orders()->get(727);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(727, $order->id);
        $this->assertEquals('processing', $order->status);
        $this->assertEquals(2, count($order->line_items));
    }

    public function test_exception_when_retrieve_an_order_detail()
    {
        $api = WooCommerceService::make();

        Http::fake([
            'http://host.docker.internal:10003/wp-json/wc/v3/orders/727' => Http::response(
                body: $this->fixture('WooCommerce/OrderDetail'),
                status: 408
            ),
        ]);

        $order = $api->orders()->get(727);

        $this->assertTrue(is_null($order));
    }

    public function test_gettings_and_storing_data_in_db()
    {
        $api = WooCommerceService::make();

        Http::fake([
            'http://host.docker.internal:10003/wp-json/wc/v3/orders/727' => Http::response(
                body: $this->fixture('WooCommerce/OrderDetail'),
                status: 200
            ),
        ]);

        $order = $api->orders()->getAndSync(727);

        $this->assertInstanceOf(WooCommerceOrder::class, $order);
        $this->assertEquals(727, $order->order_id);
        $this->assertInstanceOf(Carbon::class, $order->date_created);
        $this->assertEquals(2, $order->items->count());
    }

    public function test_getting_order_list()
    {
        $api = WooCommerceService::make();

        Http::fake([
            'http://host.docker.internal:10003/wp-json/wc/v3/orders*' => Http::response(
                body: $this->fixture('WooCommerce/OrderList'),
                status: 200
            ),
        ]);

        $orders = $api->orders()->collect();

        $this->assertInstanceOf(Collection::class, $orders);
        $this->assertEquals(2, $orders->count());
        $this->assertInstanceOf(Order::class, $orders->first());
    }

    public function test_errors_when_get_list_of_orders()
    {
        $api = WooCommerceService::make();

        Http::fake([
            'http://host.docker.internal:10003/wp-json/wc/v3/orders*' => Http::response(
                body: $this->fixture('WooCommerce/OrderList'),
                status: 408
            ),
        ]);

        $orders = $api->orders()->collect();

        $this->assertTrue(is_null($orders));
    }

    protected function getEndpointUrl(string $endpoint): string
    {
        return sprintf('%s%s', $this->apiUrl, $endpoint);
    }
}
