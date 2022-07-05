<?php

namespace Tests\Feature\Services\WooCommerce;

use App\Models\WooCommerce\Customer as WooCommerceCustomer;
use App\Services\WooCommerce\DataObjects\Customer;
use App\Services\WooCommerce\Resources\CustomerResource;
use App\Services\WooCommerce\WooCommerceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Http\BaseHttp;

class WooCommerceCustomerResourceTest extends BaseHttp
{
    use RefreshDatabase;

    public function test_customer_resource_instance()
    {
        $api = WooCommerceService::make();

        $this->assertTrue(method_exists($api, 'customers'));
        $this->assertInstanceOf(CustomerResource::class, $api->customers());
        $this->assertClassHasAttribute('endpoint', CustomerResource::class);
        $this->assertClassHasAttribute('factory', CustomerResource::class);

        $this->assertInstanceOf(WooCommerceService::class, $api->customers()->service());
    }

    public function test_getting_customer_using_customer_resource()
    {
        $api = WooCommerceService::make();

        Http::fake([
            $this->getUrl('customers/2202') => Http::response(
                body: $this->fixture('WooCommerce/CustomerDetail'),
                status: 200
            ),
        ]);

        $customer = $api->customers()->get(2202);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('ram@ram.com', $customer->email);
        $this->assertEquals('subscriber', $customer->role);
    }

    public function test_exception_when_retrieve_a_customer_detail()
    {
        $api = WooCommerceService::make();

        Http::fake([
            $this->getUrl(endpoint: 'customers/2202') => Http::response(
                body: $this->fixture('WooCommerce/CustomerDetail'),
                status: 408
            ),
        ]);

        $customer = $api->customers()->get(2202);

        $this->assertTrue(is_null($customer));
    }

    public function test_expception_when_api_returns_404()
    {
        $api = WooCommerceService::make();

        Http::fake([
            $this->getUrl(endpoint: 'customers/2202') => Http::response(
                body: $this->fixture('WooCommerce/CustomerDetail'),
                status: 404
            ),
        ]);

        $customer = $api->customers()->get(2202);

        $this->assertTrue(is_null($customer));
    }

    public function test_storing_in_db_customer_api_response()
    {
        $api = WooCommerceService::make();

        Http::fake([
            $this->getUrl(endpoint: 'customers/2202') => Http::response(
                body: $this->fixture('WooCommerce/CustomerDetail'),
                status: 200
            ),
        ]);

        $customer = $api->customers()->getAndSync(2202);

        $this->assertInstanceOf(WooCommerceCustomer::class, $customer);
        $this->assertEquals(2202, $customer->customer_id);
        $this->assertEquals('subscriber', $customer->role);
        $this->assertEquals('David Ram', $customer->getfullName());
        $this->assertEquals('http://kind.humans/wp-admin/user-edit.php?user_id=2202', $customer->getPermalinkOnStore());

        $customerLooked = WooCommerceCustomer::getByEmail(email: 'ram@ram.com');
        $this->assertEquals('David Ram', $customer->username);
    }
}
