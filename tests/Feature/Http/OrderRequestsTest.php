<?php

namespace Tests\Feature\Http;

use App\Services\Printforia\PrintforiaApiClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Tests\TestCase;

class OrderRequestsTest extends TestCase
{
    use RefreshDatabase;

    public function test_printforia_order_detail()
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
    }

    public function fixture(string $name): array
    {
        $file = file_get_contents(
            filename: base_path("tests/Fixtures/$name.json"),
        );

        if(! $file) {
            throw new InvalidArgumentException(
                message: "Cannot find fixture: [$name] at tests/Fixtures/$name.json",
            );
        }

        return json_decode(
            json: $file,
            associative: true,
        );
    }
}
