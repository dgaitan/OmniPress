<?php

namespace Tests\Feature\Http\Printforia;

use App\Services\Printforia\PrintforiaApiClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Http\BaseHttp;

class PrintforiaApiClientTest extends BaseHttp
{
    use RefreshDatabase;

    public function test_printforia_api_client_instance()
    {
        $api = new PrintforiaApiClient;

        $this->assertInstanceOf(PrintforiaApiClient::class, $api);
        $this->assertTrue(method_exists($api, 'request'));
        $this->assertTrue(method_exists($api, 'createOrder'));
        $this->assertTrue(method_exists($api, 'getOrder'));

        // Test that I can do a HTTP request from printforia API client
        $this->assertInstanceOf(PendingRequest::class, $api->request());

        Http::fake([
            'https://api.com' => Http::response(
                body: [
                    'status' => 'ok',
                    'message' => 'Have fun!'
                ],
                status: 200
            )
        ]);

        $request = $api->request()->get('https://api.com');

        $this->assertTrue($request->ok());
        $this->assertEquals(200, $request->status());
        $this->assertEquals('ok', $request->object()->status);
        $this->assertEquals('Have fun!', $request->object()->message);
    }

    public function test_printforia_api_url_builder()
    {
        $api = new PrintforiaApiClient;

        $this->assertTrue(method_exists($api, 'getApiUrl'));

        $url = $api->getApiUrl(endpoint: 'orders');

        $this->assertIsString($url);
        $this->assertEquals('https://api-sandbox.printforia.com/v2/orders', $url);
    }

    public function test_printforia_get_order_detail()
    {
        Http::fake([
            'https://api-sandbox.printforia.com/v2/orders/V1StGXR8_Z5jdHi6B-myT' => Http::response(
                body: $this->fixture('printforiaOrder'),
                status: 200
            )
        ]);

        $api = new PrintforiaApiClient;
        $request = $api->getOrder(orderId: 'V1StGXR8_Z5jdHi6B-myT');

        $this->assertTrue($request->ok());
        $this->assertEquals(200, $request->status());
        $this->assertEquals('V1StGXR8_Z5jdHi6B-myT', $request->object()->id);
        $this->assertEquals('created', $request->object()->status);
    }

    // public function test_printforia_create_order_detail()
    // {
    //     Http::fake([
    //         'https://api-sandbox.printforia.com/v2/orders' => Http::response(
    //             body: $this->fixture('printforiaOrder'),
    //             status: 200
    //         )
    //     ]);

    //     $api = new PrintforiaApiClient;
    // }
}
