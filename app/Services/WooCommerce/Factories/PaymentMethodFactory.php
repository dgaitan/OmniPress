<?php

namespace App\Services\WooCommerce\Factories;

use App\Services\Contracts\FactoryContract;
use App\Services\WooCommerce\DataObjects\PaymentMethod;

class PaymentMethodFactory implements FactoryContract
{
    public static function make(array $attributes): PaymentMethod
    {
        return new PaymentMethod($attributes);
    }
}
