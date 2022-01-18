<?php

namespace Tests\Feature\Http\Clients;

use Tests\TestCase;
use Tests\Utils\WooCommerceClientTestResponses;
use App\Http\Clients\Client;
use App\Http\Clients\WooCommerce\WooCommerceClient;

class WooCommerceClientTest extends TestCase {

    protected $client;
    protected $wooClient;
    protected $retrieveFromAPI = false;

    protected function setUp(): void {
        parent::setUp();

        $this->client = new Client;
        $this->wooClient = new WooCommerceClient($this->client);
        $this->wooClient->setTestingMode(true);
        $this->wooClient->setTestingCollectionData(WooCommerceClientTestResponses::data());
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

    public function test_get_customers_from_api() : void {
        if ($this->retrieveFromAPI) {
            $this->wooClient->retrieveDataFromAPI(true);
            $customers = $this->wooClient->getCustomers(['role' => 'administrator']);
            
            $this->assertNotNull($customers);
            $this->assertSame(1, count($customers));
        }

        $this->assertTrue(true);
    }

    public function test_get_coupons() : void {
        $coupons = $this->wooClient->getCoupons(['take' => 10]);

        $this->assertTrue(2 === count($coupons[1]));
        $this->assertNotNull($coupons[1][0]->code);
    }

    public function test_get_coupons_from_api() : void {
        if ($this->retrieveFromAPI) {
            $this->wooClient->retrieveDataFromAPI(true);
            $coupons = $this->wooClient->getCoupons();
            
            $this->assertNotNull($coupons);
            var_dump(count($coupons));
        }

        $this->assertTrue(true);
    }

    public function test_get_orders(): void {
        $orders = $this->wooClient->getOrders(['take' => 10]);

        $this->assertTrue(2 === count($orders[1]));

        $order1 = $orders[1][0];
        
        $this->assertEquals(727, $order1->order_id);
        $this->assertEquals('727', $order1->number);
        $this->assertEquals('processing', $order1->status);
        $this->assertEquals('1.0', $order1->cart_tax);
        $this->assertFalse($order1->prices_include_tax);
        
        // Billing and Shipping
        $this->assertEquals('John', $order1->billing->first_name);
        $this->assertEquals('John', $order1->shipping->first_name);
        
        // Met data
        $this->assertEquals(13106, $order1->meta_data[0]->meta_id);
        $this->assertEquals('_download_permissions_granted', $order1->meta_data[0]->key);
        $this->assertEquals('yes', $order1->meta_data[0]->value);

        // Line Items
        $this->assertEquals(2, count($order1->line_items));
        $line_item = $order1->line_items[0];
        $this->assertEquals(315, $line_item->line_item_id);
        $this->assertEquals(799, $line_item->product_id);
        $this->assertEquals('Woo Single #1', $line_item->name);
        $this->assertEquals(2, $line_item->quantity);
        $this->assertEquals(6.00, $line_item->subtotal);
    }

    public function test_get_orders_from_api() : void {
        if ($this->retrieveFromAPI) {
            $this->wooClient->retrieveDataFromAPI(true);
            $orders = $this->wooClient->getOrders(['status' => 'processing']);
            
            $this->assertNotNull($orders);
        }

        $this->assertTrue(true);
    }

    public function test_get_products() : void {
        $products = $this->wooClient->getProducts();

        $this->assertTrue(2 === count($products[1]));

        $product = $products[1][0];
        $this->assertEquals(799, $product->product_id);
        $this->assertIsArray($product->attributes[0]->options);
    }

    public function test_get_products_from_api() : void {
        if ($this->retrieveFromAPI) {
            $this->wooClient->retrieveDataFromAPI(true);
            $products = $this->wooClient->getProducts(['status' => 'publish', 'take' => 20]);
            
            $this->assertNotNull($products);
        }

        $this->assertTrue(true);
    }
}