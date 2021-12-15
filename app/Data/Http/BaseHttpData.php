<?php

namespace App\Data\Http;

use ReflectionClass;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use stdClass;

abstract class BaseHttpData extends Data {
    public static $id_field = 'id';
    
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
        $attributes = self::getAttributes();
        $_data = [];
        
        if ($data) {
            foreach ($data as $key => $value) {
                if ($key === 'id') {
                    $_data[static::$id_field] = $value;
                }
                
                if (!in_array($key, $attributes)) {
                    continue;
                }
                
                if (is_null($value)) {
                    $value = "N/A";
                }
                
                if ($value instanceof stdClass) {
                    $_data[$key] = (array) $value;
                }

                $_data[$key] = $value;
            }
        }

        return static::from($_data);
    }

    /**
     * Get the attributes name of this data to 
     * filter the necessary attributes only
     * from the api response
     * 
     * @return array
     */
    public static function getAttributes() : array {
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