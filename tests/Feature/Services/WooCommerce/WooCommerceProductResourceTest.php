<?php

namespace Tests\Feature\Services\WooCommerce;

use App\Models\WooCommerce\Category;
use App\Models\WooCommerce\Product as WooCommerceProduct;
use App\Models\WooCommerce\ProductImage;
use App\Services\WooCommerce\DataObjects\Product;
use App\Services\WooCommerce\Resources\ProductResource;
use App\Services\WooCommerce\WooCommerceService;
use Carbon\Carbon;
use Cknow\Money\Money;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Http\BaseHttp;
use Tests\Utils\InteractsWithScout;

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
            ),
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

    public function test_exception_when_retrieve_a_product_detail()
    {
        $api = WooCommerceService::make();

        Http::fake([
            'http://host.docker.internal:10003/wp-json/wc/v3/products/794' => Http::response(
                body: $this->fixture('WooCommerce/ProductDetail'),
                status: 408
            ),
        ]);

        $products = $api->products()->get(794);

        $this->assertTrue(is_null($products));
    }

    public function test_gettings_and_storing_data_in_db()
    {
        $this->disableScout();
        $api = WooCommerceService::make();

        Http::fake([
            'http://host.docker.internal:10003/wp-json/wc/v3/products/794' => Http::response(
                body: $this->fixture('WooCommerce/ProductDetail'),
                status: 200
            ),
        ]);

        $product = $api->products()->getAndSync(794);

        $this->assertInstanceOf(WooCommerceProduct::class, $product);
        $this->assertEquals(794, $product->product_id);
        $this->assertInstanceOf(Carbon::class, $product->date_created);
        $this->assertEquals(2, $product->categories->count());
        $this->assertEquals(2, $product->images->count());

        $category = $product->categories()->whereWooCategoryId(9)->first();
        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals(9, $category->woo_category_id);
        $this->assertEquals('Clothing', $category->name);
        $this->assertEquals('clothing', $category->slug);

        $featuredImage = $product->featuredImage();
        $this->assertInstanceOf(ProductImage::class, $featuredImage);
        $this->assertEquals('https://example.com/wp-content/uploads/2017/03/T_2_front-4.jpg', $featuredImage->src);

        // testing money
        $this->assertEquals(2199, $product->price);
        $this->assertInstanceOf(Money::class, $product->getMoneyValue('price'));
        $this->assertEquals('$21.99', $product->getMoneyValue('price')->format());
    }

    public function test_product_variations_api_response_is_correct()
    {
        $api = WooCommerceService::make();

        Http::fake([
            'http://host.docker.internal:10003/wp-json/wc/v3/products/539489' => Http::response(
                body: $this->fixture('WooCommerce/ProductVariableDetail'),
                status: 200
            ),
        ]);

        $product = $api->get('products/539489');

        $this->assertTrue($product->ok());
        $this->assertEquals('variable', $product->object()->type);
        $this->assertEquals(6, count($product->object()->product_variations));
    }

    public function test_product_variation_api_resource_is_correct()
    {
        $api = WooCommerceService::make();

        Http::fake([
            'http://host.docker.internal:10003/wp-json/wc/v3/products/539489' => Http::response(
                body: $this->fixture('WooCommerce/ProductVariableDetail'),
                status: 200
            ),
        ]);

        $product = $api->products()->get(539489);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('variable', $product->type);
        $this->assertEquals(6, count($product->product_variations));
    }

    public function test_product_variation_sync()
    {
        $this->disableScout();
        $api = WooCommerceService::make();

        Http::fake([
            'http://host.docker.internal:10003/wp-json/wc/v3/products/539489' => Http::response(
                body: $this->fixture('WooCommerce/ProductVariableDetail'),
                status: 200
            ),
        ]);

        $product = $api->products()->getAndSync(539489);

        $this->assertInstanceOf(WooCommerceProduct::class, $product);
        $this->assertEquals('variable', $product->type);
        $this->assertTrue($product->variations->isNotEmpty());
        $this->assertEquals(6, $product->variations->count());

        // it must exists a product variation with this slug
        $this->assertTrue($product->variations()->whereSlug('kinja-tee-ocean-x-small')->exists());

        $productVariation = $product->variations()
            ->whereSlug('kinja-tee-ocean-x-small')
            ->first();

        $this->assertInstanceOf(WooCommerceProduct::class, $productVariation);
        $this->assertEquals(539489, $productVariation->parent->product_id);
        $this->assertEquals('Kinja Tee - Ocean - X-Small', $productVariation->name);
        $this->assertEquals('variation', $productVariation->type);

    }
}
