<?php

namespace Tests\Feature\Tasks;

use DateTime;
use Tests\TestCase;
use App\Tasks\WooCommerceTask;
use Tests\Utils\WooCommerceClientTestResponses;
use App\Models\WooCommerce\Customer;

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
}