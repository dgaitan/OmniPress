<?php

namespace Tests\Feature\Models;

use DateTime;
use App\Models\Customer;
use App\Data\Customer\MetaData;
use Spatie\LaravelData\DataCollection;

class CustomerTest extends BaseModelTest {
    
    public function test_basic_customer_model() {
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
        $this->assertSame('foo@mail.com', $customer->email);
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

    public function test_customer_model_using_meta_data_class() : void {
        $now = new DateTime;
        $meta_data = MetaData::collection([
            MetaData::from([
                'id' => 1,
                'key' => 'foo',
                'value' => 'bar'
            ])
        ]);
        
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
            'meta_data' => $meta_data->toArray()
        ]);

        $this->assertTrue( is_array( $customer->meta_data ) );
        $this->assertInstanceOf( DataCollection::class, $customer->getMeta() );

        $meta = $customer->getMeta()[0];
        $this->assertIsInt( $meta->id );
        $this->assertIsString( $meta->key );
        $this->assertIsString( $meta->value );

        $this->assertEquals( 1, $meta->id );
        $this->assertEquals( 'foo', $meta->key );
        $this->assertEquals( 'bar', $meta->value );
    }
}