<?php

namespace App\Casts;

use App\Data\Coupon\CouponSettingData;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class CouponSetting implements CastsAttributes
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
        return CouponSettingData::from(json_decode($value, true));
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
