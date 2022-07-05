<?php

namespace Tests\Feature\Services\WooCommerce;

use App\Services\WooCommerce\Resources\OrderResource;
use App\Services\WooCommerce\WooCommerceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        // Http::fake([
        //     $this->getEndpointUrl()
        // ]);
    }

    protected function getEndpointUrl(string $endpoint): string
    {
        return sprintf('%s%s', $this->apiUrl, $endpoint);
    }
}
