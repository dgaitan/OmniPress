<?php

namespace Tests\Feature\Services\WooCommerce;

use App\Models\WooCommerce\PaymentMethod as WooCommercePaymentMethod;
use App\Services\WooCommerce\DataObjects\PaymentMethod;
use App\Services\WooCommerce\Resources\PaymentMethodResource;
use App\Services\WooCommerce\WooCommerceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Http\BaseHttp;

class WooCommercePaymentMethodTest extends BaseHttp
{
    use RefreshDatabase;

    public function test_payment_method_resource_instance()
    {
        $api = WooCommerceService::make();

        $this->assertTrue(method_exists($api, 'paymentMethods'));
        $this->assertInstanceOf(PaymentMethodResource::class, $api->paymentMethods());
        $this->assertClassHasAttribute('endpoint', PaymentMethodResource::class);
        $this->assertClassHasAttribute('factory', PaymentMethodResource::class);

        $this->assertInstanceOf(WooCommerceService::class, $api->paymentMethods()->service());
    }

    public function test_getting_payment_methods_using_payment_method_resource()
    {
        $api = WooCommerceService::make();

        Http::fake([
            $this->getUrl('payment_gateways/kindhumans_stripe_gateway') => Http::response(
                body: $this->fixture('WooCommerce/PaymentMethodDetail'),
                status: 200
            ),
        ]);

        $paymentMethod = $api->paymentMethods()->get('kindhumans_stripe_gateway');

        $this->assertInstanceOf(PaymentMethod::class, $paymentMethod);
        $this->assertEquals('kindhumans_stripe_gateway', $paymentMethod->id);
    }

    public function test_exception_when_retrieve_a_payment_method_detail()
    {
        $api = WooCommerceService::make();

        Http::fake([
            $this->getUrl(endpoint: 'payment_gateways/kindhumans_stripe_gateway') => Http::response(
                body: $this->fixture('WooCommerce/PaymentMethodDetail'),
                status: 408
            ),
        ]);

        $paymentMethods = $api->paymentMethods()->get('kindhumans_stripe_gateway');

        $this->assertTrue(is_null($paymentMethods));
    }

    public function test_expception_when_api_returns_404()
    {
        $api = WooCommerceService::make();

        Http::fake([
            $this->getUrl(endpoint: 'payment_gateways/kindhumans_stripe_gateway') => Http::response(
                body: $this->fixture('WooCommerce/PaymentMethodDetail'),
                status: 404
            ),
        ]);

        $paymentMethod = $api->paymentMethods()->get('kindhumans_stripe_gateway');

        $this->assertTrue(is_null($paymentMethod));
    }

    public function test_storing_in_db_payment_method_api_response()
    {
        $api = WooCommerceService::make();

        Http::fake([
            $this->getUrl(endpoint: 'payment_gateways/kindhumans_stripe_gateway') => Http::response(
                body: $this->fixture('WooCommerce/PaymentMethodDetail'),
                status: 200
            ),
        ]);

        $paymentMethod = $api->paymentMethods()->getAndSync('kindhumans_stripe_gateway');

        $this->assertInstanceOf(WooCommercePaymentMethod::class, $paymentMethod);
        $this->assertEquals('kindhumans_stripe_gateway', $paymentMethod->payment_method_id);
        $this->assertEquals('Credit Card', $paymentMethod->title);
        $this->assertEquals('Kindhumans Stripe Gateway', $paymentMethod->method_title);
        $this->assertEquals(
            'Custom Stripe Gateway for Kindhumans Checkout',
            $paymentMethod->method_description
        );
    }

    public function test_get_payment_methods_using_resources()
    {
        $api = WooCommerceService::make();

        Http::fake([
            $this->getUrl(endpoint: 'payment_gateways*') => Http::response(
                body: $this->fixture('WooCommerce/PaymentMethodsList'),
                status: 200
            ),
        ]);

        $paymentMethods = $api->paymentMethods()->collect();
        $this->assertEquals(6, $paymentMethods->count());
    }

    public function test_collect_payment_methods_with_resources()
    {
        $api = WooCommerceService::make();

        Http::fake([
            $this->getUrl(endpoint: 'payment_gateways*') => Http::response(
                body: $this->fixture('WooCommerce/PaymentMethodsList'),
                status: 200
            ),
        ]);

        $paymentMethods = $api->paymentMethods()->collectAndSync();

        $this->assertTrue(! is_null($paymentMethods));
        $this->assertEquals(6, $paymentMethods->count());
        $this->assertInstanceOf(WooCommercePaymentMethod::class, $paymentMethods->first());
    }
}
