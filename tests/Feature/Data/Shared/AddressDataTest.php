<?php

namespace Tests\Feature\Data\Shared;

use App\Data\Shared\AddressData;
use Tests\TestCase;

class AddressDataTest extends TestCase
{
    public function test_basic_address_instance(): void
    {
        $address = [
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
            'phone' => '12345667',
        ];
        $shipping_address = AddressData::from($address);

        $this->assertInstanceOf(AddressData::class, $shipping_address);
        $this->assertSame($address, $shipping_address->toArray());
    }
}
