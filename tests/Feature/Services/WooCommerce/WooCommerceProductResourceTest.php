<?php

namespace Tests\Feature\Services\WooCommerce;

use App\Models\WooCommerce\Product as WooCommerceProduct;
use App\Services\WooCommerce\DataObjects\Product;
use App\Services\WooCommerce\Resources\ProductResource;
use App\Services\WooCommerce\WooCommerceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Http\BaseHttp;
use Tests\Utils\InteractsWithScout;
use Carbon\Carbon;

class WooCommerceProductResourceTest extends BaseHttp
{
    use RefreshDatabase;
    use InteractsWithScout;

    public function test_product_resource_instance()
    {
        $api = WooCommerceService::make();

        $this->assertInstanceOf(ProductResource::class, $api->products());
        $this->assertClassHasAttribute('endpoint', ProductResource::class);
        $this->assertClassHasAttribute('factory', ProductResource::class);

        $this->assertInstanceOf(WooCommerceService::class, $api->products()->service());
    }

    public function test_getting_product_using_product_resource()
    {
        $api = WooCommerceService::make();

        Http::fake([
            '*' => Http::response(
                body: $this->fixture('WooCommerce/ProductDetail'),
                status: 200
            )
        ]);

        $product = $api->products()->get(794);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals(794, $product->id);
        $this->assertEquals('premium-quality-19', $product->slug);
        $this->assertEquals('simple', $product->type);
        $this->assertEquals('publish', $product->status);
        $this->assertEquals('2017-03-23T17:01:14', $product->date_created);
        $this->assertEquals(2, count($product->images));
    }

    public function test_exception_when_retrieve_an_order_detail()
    {
        $api = WooCommerceService::make();

        Http::fake([
            '*' => Http::response(
                body: $this->fixture('WooCommerce/ProductDetail'),
                status: 408
            )
        ]);

        $products = $api->products()->get(794);

        $this->assertTrue(is_null($products));
    }

    public function test_gettings_and_storing_data_in_db()
    {
        $this->disableScout();
        $api = WooCommerceService::make();

        Http::fake([
            '*' => Http::response(
                body: $this->fixture('WooCommerce/ProductDetail'),
                status: 200
            )
        ]);

        $product = $api->products()->getAndSync(794);

        $this->assertInstanceOf(WooCommerceProduct::class, $product);
        $this->assertEquals(794, $product->product_id);
        $this->assertInstanceOf(Carbon::class, $product->date_created);
        $this->assertEquals(2, $product->categories->count());
        $this->assertEquals(2, $product->images->count());
    }
}
