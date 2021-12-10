<?php

namespace Tests\Feature\Data\Customer;

use App\Data\Customer\MetaData;
use Tests\TestCase;

class MetaDataTest extends TestCase {

    public function test_meta_data_using_from_static_method() : void {
        $data = array(
            'id' => 1,
            'key' => 'foo',
            'value' => 'bar'
        );
        
        $meta_data = MetaData::from( $data );

        $this->assertInstanceOf( MetaData::class, $meta_data );
        $this->assertSame( $data, $meta_data->toArray() );
        $this->assertSame( json_encode( $data ), $meta_data->toJson() );

        //
        $this->assertEquals( 1, $meta_data->id );
        $this->assertEquals( 'foo', $meta_data->key );
        $this->assertEquals( 'bar', $meta_data->value );
    }

    public function test_meta_data_using_constructor() : void {
        $data = array(
            'id' => 1,
            'key' => 'foo',
            'value' => 'bar'
        );

        $meta_data = new MetaData(1, "foo", "bar");
        $this->assertInstanceOf( MetaData::class, $meta_data );
        $this->assertSame( $data, $meta_data->toArray() );
        $this->assertSame( json_encode( $data ), $meta_data->toJson() );

        //
        $this->assertEquals( 1, $meta_data->id );
        $this->assertEquals( 'foo', $meta_data->key );
        $this->assertEquals( 'bar', $meta_data->value );
    }

    public function test_meta_data_collection() : void {
        $data = array(
            'id' => 1,
            'key' => 'foo',
            'value' => 'bar'
        );
        $data_2 = array(
            'id' => 2,
            'key' => 'foo_2',
            'value' => 'bar_2'
        );

        $datas = MetaData::collection([ $data, $data_2 ]);

        $this->assertEquals(2, count($datas));
    }

    public function test_meta_data_collection_from_instance() : void {
        $data = MetaData::from(array(
            'id' => 1,
            'key' => 'foo',
            'value' => 'bar'
        ));
        $data_2 = MetaData::from(array(
            'id' => 2,
            'key' => 'foo_2',
            'value' => 'bar_2'
        ));

        $datas = MetaData::collection([ $data, $data_2 ]);

        $this->assertEquals(2, count($datas));
    }
}