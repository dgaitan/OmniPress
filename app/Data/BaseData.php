<?php

namespace App\Data;

use ReflectionClass;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use stdClass;

abstract class BaseData extends Data {
    /**
     * This is the id name to map later
     * in the database.
     * 
     * ie: order should have present order_id
     * 
     * @var string
     */
    public static $id_field = 'id';

    /**
     * Fields that should be a monetary value.
     * 
     * ie: total, subtotal, etc...
     * 
     * @var array
     */
    protected static $priceFields = [];

    /**
     * Fields that should be collection.
     * 
     * ie: MetaData, LineItems, etc...
     * 
     * [
     *   'meta_data' => \App\Data\Shared\MetaData::class
     * ]
     * 
     * @var array
     */
    protected static $collectionFields = [];

    /**
     * Data Attributes of this data class.
     * This will be loaded dinamicly.
     * 
     * @var array
     */
    protected static $dataAttributes = [];
    
    /**
     * Collect the data from API response.
     * 
     * @param array $response
     * @return DataCollection
     */
    public static function collectFromResponse(array $response = []) : DataCollection {
        $elements = [];

        if ($response) {
            foreach ($response as $data) {
                $elements[] = static::_fromResponse((array) $data);
            }
        }

        return static::collection($elements);
    }

    /**
     * Load the response and validate it to load well every data
     * 
     * @param array $data - single data
     * @return static
     */
    public static function _fromResponse(array $data) : static {
        $data = json_encode($data);
        $data = json_decode($data, true);
        // dd(static::_processResponse($data));
        // dd(static::from(static::_processResponse($data)));
        return static::from(static::_processResponse($data));
    }

    /**
     * Process the http response and return it validated
     * to convert in a DataClass
     * 
     * @param array $data - Http Response
     * @return array Data ready to be a DataClass
     */
    public static function _processResponse(array $data): array {
        $_data = [];

        if ($data) {
            foreach ($data as $key => $value) {
                if ($key === 'id') {
                    $_data[static::$id_field] = $value;
                }

                $value = self::_processResponseItem($key, $value);
                
                if (!is_null($value)) {
                    $_data[$key] = $value;
                }
            }
        }

        return $_data;
    }

    /**
     * Process a Reponse Item
     * 
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public static function _processResponseItem(string $key, mixed $value): mixed {
        if (!in_array($key, static::getAttributes())) {
            return null;
        }

        if (static::isPriceField($key)) {
            return static::processPriceField($value);
        }

        if ($value instanceof stdClass) {
            $value = (array) $value;
        }
        
        if (static::isCollectionField($key)) {
            return static::processCollection($key, $value);
        }

        if (is_null($value)) {
            return null;
        }

        return $value;
    }

    /**
     * Check if a field should has be stored with a monteray value.
     * 
     * @param string $field - the field name
     * @return boold
     */
    public static function isPriceField(string $field): bool {
        return in_array($field, static::$priceFields);
    }

    /**
     * Process Price Field
     * 
     * @var string|int|float $value
     * @return int
     */
    public static function processPriceField(string|int|float $value): float {
        if (empty($value) || is_null($value)) {
            return 0;
        }
        
        return (float) $value;
    }

    /**
     * Check if a field is a collection field
     * 
     * @param string $field
     * @return bool
     */
    public static function isCollectionField(string $field): bool {
        return array_key_exists($field, static::$collectionFields);
    }

    /**
     * Process the collection class
     * 
     * @param string $collectioName - the collection name
     * @param array $data - the data attached to the collection
     * @param array the collection processed.
     */
    public static function processCollection(string $collectionName, array $data): array {
        $collection = [];

        if ($data) {
            foreach ($data as $value) {
                $collection[] = static::$collectionFields[$collectionName]::_processResponse($value);
            }
        }
        
        return $collection;
    }

    /**
     * Get the attributes name of this data to 
     * filter the necessary attributes only
     * from the api response
     * 
     * @return array
     */
    public static function getAttributes() : array {
        if (static::$dataAttributes) {
            return static::$dataAttributes;
        }
        
        $attributes = [];
        $params = (new ReflectionClass(static::class))
            ->getConstructor()
            ->getParameters();

        if ($params) {
            foreach ($params as $param) {
                $attributes[] = $param->name;
            }
        }
        

        return $attributes;
    }
}