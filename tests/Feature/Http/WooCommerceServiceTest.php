<?php

namespace Tests\Feature\Http;

use App\Services\WooCommerce\WooCommerceService;
use Automattic\WooCommerce\Client as WooCommerce;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class WooCommerceServiceTest extends BaseHttp
{
    use RefreshDatabase;

    protected $apiUrl = 'http://host.docker.internal:10003/wp-json/wc/v3/';

    public function test_woocommerce_client_instance()
    {
        $api = WooCommerceService::make();

        $this->assertClassHasAttribute('key', WooCommerceService::class);
        $this->assertClassHasAttribute('secret', WooCommerceService::class);
        $this->assertClassHasAttribute('domain', WooCommerceService::class);

        $this->assertInstanceOf(WooCommerceService::class, $api);

        // test makeRequest method
        $this->assertTrue(method_exists($api, 'makeRequest'));
        $this->assertInstanceOf(WooCommerce::class, $api->makeRequest());

        // test request method
        $this->assertTrue(method_exists($api, 'request'));
        $this->assertInstanceOf(PendingRequest::class, $api->request());

        $this->assertTrue(method_exists($api, 'get'));
        $this->assertTrue(method_exists($api, 'post'));
        $this->assertTrue(method_exists($api, 'getEndpointUrl'));

        $this->assertTrue(method_exists($api, 'orders'));
        $this->assertTrue(method_exists($api, 'products'));
        $this->assertTrue(method_exists($api, 'causes'));
        $this->assertTrue(method_exists($api, 'customers'));
        $this->assertTrue(method_exists($api, 'paymentMethods'));
        $this->assertTrue(method_exists($api, 'memberships'));
    }

    public function test_woocommerce_client_requests()
    {
        $api = WooCommerceService::make();

        Http::fake([
            'https://api.com' => Http::response(
                body: [
                    'status' => 'ok',
                    'message' => 'Have fun!',
                ],
                status: 200
            ),
        ]);

        $res = $api->request()->get('https://api.com');
        $this->assertTrue($res->ok());
        $this->assertEquals($res->object()->status, 'ok');
        $this->assertEquals(200, $res->status());
    }

    public function test_get_order_using_http_client()
    {
        $api = WooCommerceService::make();

        Http::fake([
            $this->getUrl('orders/727') => Http::response(
                body: $this->fixture('WooCommerce/OrderDetail'),
                status: 200
            ),
        ]);

        $res = $api->get('orders/727');

        $this->assertTrue($res->ok());
        $this->assertEquals(200, $res->status());
        $this->assertEquals(727, $res->object()->id);
        $this->assertEquals('processing', $res->object()->status);
        $this->assertEquals('2017-03-22T16:28:02', $res->object()->date_created);
        $this->assertIsObject($res->object()->billing);
    }

    protected function getEndpointUrl(string $endpoint): string
    {
        return sprintf('%s%s', $this->apiUrl, $endpoint);
    }
}
