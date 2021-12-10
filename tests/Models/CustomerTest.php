<?php

namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Customer;
use DateTime;

class CustomerTest extends TestCase {
    
    public function test_basic_customer_model() : void {
        $now = new DateTime;
        $customer = Customer::firstOrCreate([
            'customer_id' => 123456,
            'date_created' => $now->format('Y/m/d H:i:s'),
            'date_modified' => $now->format('Y/m/d H:i:s'),
            'email' => 'foo@mail.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'role' => 'customer',
            'username' => 'john_doe',
            'billing' => array(),
            'shipping' => array(),
            'is_paying_customer' => true,
            'avatar_url' => 'foooo',
            'meta_data' => array(
                array(
                    'id' => 1,
                    'key' => 'foo',
                    'value' => 'bar'
                )
            )
        ]);

        $this->assertSame(123456, $customer->customer_id);
        $this->assertSame($now->format('Y/m/d H:i:s'), $customer->date_created);
        $this->assertSame('foo@mail', $customer->email);
        $this->assertEquals(array(), $customer->billing);
        $this->assertTrue(is_array($customer->billing));
        $this->assertTrue($customer->is_paying_customer);
        $this->assertEquals(
            array(
                array(
                    'id' => 1,
                    'key' => 'foo',
                    'value' => 'bar'
                )
            ),
            $customer->meta_data
        );
    }
}