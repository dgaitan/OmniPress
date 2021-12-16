<?php

namespace Tests\Feature\Http\Clients;

use Tests\TestCase;
use Tests\Utils\WooCommerceClientTestResponses;
use App\Http\Clients\Client;
use App\Http\Clients\WooCommerce\WooCommerceClient;

class WooCommerceClientTest extends TestCase {

    protected $service;
    protected $client;
    protected $wooClient;

    protected function setUp(): void {
        parent::setUp();

        $this->service = $this->create_service();
        $this->client = Client::initialize($this->service);
        $this->wooClient = new WooCommerceClient($this->client);
        $this->wooClient->setTestingMode(true);
        $this->wooClient->setTestingData(WooCommerceClientTestResponses::data());
    }
    
    public function test_get_customers() : void {
        $customers = $this->wooClient->getCustomers();

        $this->assertTrue(2 === count($customers[1]));

        foreach ($customers[1] as $customer) {
            if ($customer->email === 'joao.silva@example.com') {
                $this->assertEquals('João', $customer->first_name);
                $this->assertEquals(26, $customer->customer_id);
                $this->assertEquals('joao.silva', $customer->username);
                $this->assertFalse($customer->is_paying_customer);
                
                // Test some of shipping
                $this->assertEquals('12345-000', $customer->shipping->postcode);
                $this->assertEquals('BR', $customer->shipping->country);

                // Test some of billing
                $this->assertEquals('joao.silva@example.com', $customer->billing->email);
            }
        }
    }

    public function test_get_coupons() : void {
        $coupons = $this->wooClient->getCoupons(['take' => 10]);

        $this->assertTrue(2 === count($coupons[1]));
    }

    // public function test_get_orders(): void {
    //     $wooClient = new WooCommerceClient($this->client);
    //     $orders = $wooClient->getOrders(['take' => 10]);

    //     $this->assertTrue(10 === count($orders[1]));
    // }
}