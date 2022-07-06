<?php

namespace Tests\Feature\Services\WooCommerce;

use App\Models\WooCommerce\Customer as WooCommerceCustomer;
use App\Services\WooCommerce\DataObjects\Customer;
use App\Services\WooCommerce\DataObjects\PaymentMethod;
use App\Services\WooCommerce\Resources\CustomerResource;
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

    // public function test_exception_when_retrieve_a_customer_detail()
    // {
    //     $api = WooCommerceService::make();

    //     Http::fake([
    //         $this->getUrl(endpoint: 'customers/2202') => Http::response(
    //             body: $this->fixture('WooCommerce/CustomerDetail'),
    //             status: 408
    //         ),
    //     ]);

    //     $customer = $api->customers()->get(2202);

    //     $this->assertTrue(is_null($customer));
    // }

    // public function test_expception_when_api_returns_404()
    // {
    //     $api = WooCommerceService::make();

    //     Http::fake([
    //         $this->getUrl(endpoint: 'customers/2202') => Http::response(
    //             body: $this->fixture('WooCommerce/CustomerDetail'),
    //             status: 404
    //         ),
    //     ]);

    //     $customer = $api->customers()->get(2202);

    //     $this->assertTrue(is_null($customer));
    // }

    // public function test_storing_in_db_customer_api_response()
    // {
    //     $api = WooCommerceService::make();

    //     Http::fake([
    //         $this->getUrl(endpoint: 'customers/2202') => Http::response(
    //             body: $this->fixture('WooCommerce/CustomerDetail'),
    //             status: 200
    //         ),
    //     ]);

    //     $customer = $api->customers()->getAndSync(2202);

    //     $this->assertInstanceOf(WooCommerceCustomer::class, $customer);
    //     $this->assertEquals(2202, $customer->customer_id);
    //     $this->assertEquals('subscriber', $customer->role);
    //     $this->assertEquals('David Ram', $customer->getfullName());
    //     $this->assertEquals('http://kind.humans/wp-admin/user-edit.php?user_id=2202', $customer->getPermalinkOnStore());

    //     $customerLooked = WooCommerceCustomer::getByEmail(email: 'ram@ram.com');
    //     $this->assertEquals('David Ram', $customer->username);
    // }
}
