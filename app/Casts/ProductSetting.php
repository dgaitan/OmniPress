<?php

namespace App\Casts;

use App\Data\Product\ProductSettingData;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class ProductSetting implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        return ! is_null($value) ? ProductSettingData::from(json_decode($value, true)) : $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        if (! is_string($value) || is_array($value)) {
            $value = json_encode($value);
        }

        return $value;
    }
}
