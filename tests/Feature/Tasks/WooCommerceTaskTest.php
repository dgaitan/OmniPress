<?php

namespace Tests\Feature\Tasks;

use DateTime;
use Tests\TestCase;
use App\Tasks\WooCommerceTask;
use Tests\Utils\WooCommerceClientTestResponses;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Coupon;
use App\Models\WooCommerce\Order;

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

        $customer = Customer::where('customer_id', 26)->first();
        $id = $customer->id;

        $this->assertEquals('joao.silva@example.com', $customer->email);
        $this->assertEquals('Rio de Janeiro', $customer->billing->city);
        $this->assertFalse($customer->is_paying_customer);

        $dateCreated = new DateTime('2017-03-21T16:11:14');
        $this->assertEquals($dateCreated->format('F j, Y'), $customer->date_created->format('F j, Y'));

        // Now we are going to tty sync it again to be sure we are updating the same customer
        $this->wooTask->syncCustomers();

        $customer = Customer::where('customer_id', 26)->get();

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
        
        $this->assertEquals(26, $order->customer->customer_id);
        $this->assertEquals('joao.silva@example.com', $order->customer->email);
        $this->assertEquals('Rio de Janeiro', $order->customer->billing->city);
        $this->assertFalse($order->customer->is_paying_customer);

    }
}