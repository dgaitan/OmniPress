<?php

namespace App\Casts;

use App\Data\Order\CouponLineData;
use App\Data\Order\FeeLineData;
use App\Data\Order\ShippingLineData;
use App\Data\Order\TaxLineData;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class OrderLines implements CastsAttributes
{
    protected $dataHandlers = [
        'tax_lines' => TaxLineData::class,
        'shipping_lines' => ShippingLineData::class,
        'fee_lines' => FeeLineData::class,
        'coupon_lines' => CouponLineData::class,
    ];

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
        if (! array_key_exists($key, $this->dataHandlers) || ! $value) {
            return $value;
        }

        return $this->dataHandlers[$key]::collection(json_decode($value, true));
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
