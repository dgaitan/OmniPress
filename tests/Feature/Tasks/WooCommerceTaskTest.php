<?php

namespace Tests\Feature\Tasks;

use DateTime;
use Tests\TestCase;
use App\Tasks\WooCommerceTask;
use Tests\Utils\WooCommerceClientTestResponses;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Coupon;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Product;

class WooCommerceTaskTest extends TestCase {

    protected $wooTask;
    protected $service;

    protected function setUp(): void {
        parent::setUp();

        $this->service = $this->create_service();
        $this->wooTask = new WooCommerceTask($this->service);
        $this->wooTask->setTestingMode(true);
        $this->wooTask->setTestingCollectionData(WooCommerceClientTestResponses::data());
    }

    public function test_customers_task(): void {
        $this->wooTask->syncCustomers();

        $customer = Customer::whereCustomerId(26)->first();
        $id = $customer->id;

        $this->assertEquals('joao.silva@example.com', $customer->email);
        $this->assertEquals('Rio de Janeiro', $customer->billing->city);
        $this->assertFalse($customer->is_paying_customer);

        $dateCreated = new DateTime('2017-03-21T16:11:14');
        $this->assertEquals($dateCreated->format('F j, Y'), $customer->date_created->format('F j, Y'));

        // Now we are going to tty sync it again to be sure we are updating the same customer
        $this->wooTask->syncCustomers();

        $customer = Customer::whereCustomerId(26);

        // We should have only 1 record with customer_id -> 26
        $this->assertEquals(1, $customer->count());
        
        $customer = $customer->first();
        $this->assertEquals($id, $customer->id); // be sure we are retriving the same object
        $this->assertEquals(26, $customer->customer_id);
        $this->assertEquals(
            'https://secure.gravatar.com/avatar/be7b5febff88a2d947c3289e90cdf017?s=96',
            $customer->avatar_url
        );
        $this->assertEquals('customer', $customer->role);
    }

    public function test_coupon_task(): void {
        $this->wooTask->syncCoupons();

        $coupons = Coupon::all();
        $this->assertNotNull($coupons);
        $this->assertTrue(2 == $coupons->count());

        $coupon = $coupons[0];
        $id = $coupon->id;

        $this->assertNotNull($coupon);
        $this->assertEquals('free shipping', $coupon->code);
        $this->assertEquals('fixed_cart', $coupon->discount_type);

        // Test settings
        $this->assertTrue($coupon->settings->free_shipping);
        $this->assertFalse($coupon->settings->exclude_sale_items);

        // Run sync again just to be sure we are not overwritting the coupons
        $this->wooTask->syncCoupons();

        $coupons = Coupon::all();
        $this->assertTrue(2 == $coupons->count()); // should we have only 2 coupons
        $this->assertSame($id, $coupons[0]->id); // Be sure is the same instance.
    }

    public function test_product_task() : void {
        $this->wooTask->syncProducts();

        $products = Product::all();
        $this->assertNotNull($products);
        $this->assertTrue(2 === $products->count());

        $product = Product::whereProductId(799)->first();

        $this->assertEquals('Ship Your Idea', $product->name);
        $this->assertEquals('ship-your-idea-22', $product->slug);
    }

    public function test_order_task() : void {
        $this->wooTask->syncCustomers(); // Is necesary to attach customer to order
        $this->wooTask->syncOrders();

        $orders = Order::all();
        $this->assertNotNull($orders);

        $order = Order::where('order_id', 723)->with('customer')->first();
        $this->assertEquals('wc_order_58d17c18352', $order->order_key);
        $this->assertEquals('checkout', $order->created_via);
        $this->assertEquals('completed', $order->status);
        $this->assertEquals('USD', $order->currency);

        $this->assertEquals(10.00, $order->shipping_total);
        $this->assertEquals(39.00, $order->total);
        $this->assertEquals(49.00, $order->shipping_total + $order->total);

        $this->assertEquals(
            'mozilla/5.0 (x11; ubuntu; linux x86_64; rv:52.0) gecko/20100101 firefox/52.0',
            $order->customer_user_agent
        );
        
        // Test Order Customer
        $this->assertEquals(26, $order->customer->customer_id);
        $this->assertEquals('joao.silva@example.com', $order->customer->email);
        $this->assertEquals('Rio de Janeiro', $order->customer->billing->city);
        $this->assertFalse($order->customer->is_paying_customer);

        // Test Shipping And Shipping
        $this->assertEquals('João', $order->billing->first_name);
        $this->assertEquals('Silva', $order->billing->last_name);
        $this->assertEquals('Av. Brasil, 432', $order->billing->address_1);
        $this->assertEquals('Rio de Janeiro', $order->shipping->city);

        // test meta data
        $this->assertTrue(1 === count($order->meta_data));
        $this->assertEquals(13023, $order->meta_data[0]->meta_id);
    }
}