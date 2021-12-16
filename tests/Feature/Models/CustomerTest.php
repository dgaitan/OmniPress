<?php

namespace Tests\Feature\Models;

use DateTime;
use App\Models\WooCommerce\Customer;
use App\Data\Customer\MetaData;
use App\Data\Shared\AddressData;
use App\Enums\CustomerRole;

class CustomerTest extends BaseModelTest {
    
    public function test_basic_customer_model() {
        $now = new DateTime;
        
        $meta_data = MetaData::collection([
            MetaData::from([
                'id' => 1,
                'key' => 'foo',
                'value' => 'bar'
            ])
        ]);

        $billing = AddressData::from($this->get_address_data());
        $shipping = AddressData::from($this->get_shipping_address());
        
        $customer = Customer::firstOrCreate([
            'customer_id' => 123456,
            'date_created' => $now->format('Y/m/d H:i:s'),
            'date_modified' => $now->format('Y/m/d H:i:s'),
            'email' => 'foo@mail.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'role' => CustomerRole::CUSTOMER(),
            'username' => 'john_doe',
            'billing' => $billing->toJson(),
            'shipping' => $shipping->toJson(),
            'is_paying_customer' => true,
            'avatar_url' => 'foooo',
            'meta_data' => $meta_data->toJson()
        ]);

        $this->assertSame(123456, $customer->customer_id);
        $this->assertSame($now->format('Y/m/d H:i:s'), $customer->date_created);
        $this->assertSame('foo@mail.com', $customer->email);
        $this->assertEquals($billing, $customer->billing);
        $this->assertInstanceOf(AddressData::class, $customer->billing);
        $this->assertTrue($customer->is_paying_customer);
        $this->assertEquals(1, count( $customer->meta_data ));
        $this->assertEquals('customer', $customer->role);
        $this->assertEquals(CustomerRole::CUSTOMER(), $customer->role);

        $this->assertIsInt( $customer->meta_data[0]->id );

        // Test BIlling
        $this->assertEquals('John', $customer->billing->first_name);
        $this->assertEquals('John Shipping', $customer->shipping->first_name);
    }

    protected function get_shipping_address() : array {
        $address = $this->get_address_data();
        $address['first_name'] = 'John Shipping';
        $address['last_name'] = 'Doe Shipping';

        return $address;
    }

    protected function get_address_data() : array {
        return [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'company' => 'kindhumans',
            'address_1' => 'foo',
            'address_2' => 'bar',
            'city' => 'Encinitas',
            'state'=> 'California',
            'postcode' => '92081',
            'country' => 'US',
            'email' => 'john@doe.com',
            'phone' => '12345667'
        ];
    }
}