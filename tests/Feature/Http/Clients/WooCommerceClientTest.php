<?php

namespace Tests\Feature\Http\Clients;

use Tests\TestCase;
use App\Http\Clients\Client;
use App\Http\Clients\WooCommerce\WooCommerceClient;

class WooCommerceClientTest extends TestCase {

    protected $service;
    protected $client;

    protected function setUp(): void {
        parent::setUp();

        $this->service = $this->create_service();
        $this->client = Client::initialize($this->service);
    }
    
    public function test_get_customers() : void {
        $wooClient = new WooCommerceClient($this->client);
        $customers = $wooClient->getCustomers(['take' => 10]);

        $this->assertTrue(10 === count($customers[1]));

        foreach ($customers[1] as $customer) {
            if ($customer->email === 'Aaronkoher@gmail.com') {
                $this->assertEquals('Aaron', $customer->first_name);
                $this->assertEquals(1381, $customer->customer_id);
                $this->assertEquals('Aaron Koher', $customer->username);
                $this->assertTrue($customer->is_paying_customer);
                
                // Test some of shipping
                $this->assertEquals('34287', $customer->shipping->postcode);
                $this->assertEquals('US', $customer->shipping->country);

                // Test some of billing
                $this->assertEquals('Aaronkoher@gmail.com', $customer->billing->email);
            }
        }
    }

    public function test_get_coupons() : void {
        $wooClient = new WooCommerceClient($this->client);
        $coupons = $wooClient->getCoupons(['take' => 10]);

        $this->assertTrue(10 === count($coupons[1]));
    }

    public function test_get_orders(): void {
        $wooClient = new WooCommerceClient($this->client);
        $orders = $wooClient->getOrders(['take' => 10]);

        $this->assertTrue(10 === count($orders[1]));
    }
}