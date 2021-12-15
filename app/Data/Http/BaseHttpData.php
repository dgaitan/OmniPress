<?php

namespace App\Data\Http;

use ReflectionClass;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use stdClass;

abstract class BaseHttpData extends Data {

    public static function collectFromResponse(array $response = []) : DataCollection {
        $elements = [];

        if ($response) {
            foreach ($response as $data) {
                $elements[] = static::_fromResponse((array) $data);
            }
        }

        return static::collection($elements);
    }

    public static function _fromResponse(array $data) : static { 
        $attributes = self::getAttributes();
        $_data = [];
        
        if ($data) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $attributes)) {
                    continue;
                }
                
                if (!$data[$key]) {
                    $_data[$key] = "N/A";
                }
                
                if ($value instanceof stdClass) {
                    $_data[$key] = (array) $value;
                }
            }
        }

        return static::from($_data);
    }

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