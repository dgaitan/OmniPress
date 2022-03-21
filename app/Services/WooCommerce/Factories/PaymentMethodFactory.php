<?php

namespace App\Services\WooCommerce\Factories;

use App\Services\WooCommerce\DataObjects\PaymentMethod;
use App\Services\Contracts\FactoryContract;

class PaymentMethodFactory implements FactoryContract
{
    public static function make(array $attributes): PaymentMethod
    {
        return new PaymentMethod($attributes);
    }
}
