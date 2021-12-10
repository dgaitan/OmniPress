<?php

namespace App\Helpers\Models;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use App\Data\Shared\AddressData;

trait Jsonable {

    /**
     * Get Data Collection from a json value.
     * 
     * @param string $data - A class that extends from Spatie\LaravelData\Data
     * @param string $value - normally should be an json in string format
     * @return DataCollection
     */
    protected function getDataCollectionFrom(
        string $data, string $value
    ) : DataCollection {
        return $data::collection($this->getJsonField($value));
    }

    /**
     * Get Data From a json
     * 
     * @param Data $data - A class that extends from Spatie\LaravelData\Data
     * @param string $value - normally should be an json in string format
     * @return Data 
     */
    protected function getDataFrom(
        string $data, string $value
    ) : Data {
        return $data::from( $this->getJsonField($value) );
    }

    /**
     * Generate a Data Collection and return a json string to store in db
     * 
     * @param string $data - A class that extends from Spatie\LaravelData\Data
     * @param string $value - normally should be an json in string format
     * @return string
     */
    protected function getCollectionJson(
        string $data, string $value
    ) : string {
        return $data::collection($this->getJsonField($value))->toJson();
    }

    /**
     * Get a data in json format to be stored
     * 
     * @param string $data - A class that extends from Spatie\LaravelData\Data
     * @param string $value - normally should be an json in string format
     * @return string
     */
    protected function getDataJson(
        string $data, string $value
    ) : string {
        return $data::from($this->getJsonField($value))->toJson();
    }

    protected function getAddressDataJson($address) : string {
        return $this->getDataJson(AddressData::class, $address);
    }

    protected function getAddressData($address) : AddressData {
        return $this->getDataFrom(AddressData::class, $address);
    }

    /**
     * Decode a json string to an array
     * 
     * @param string $value
     * @return array
     */
    protected function getJsonField($value) : array {
        return json_decode($value, true);
    }
}