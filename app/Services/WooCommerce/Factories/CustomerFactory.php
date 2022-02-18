<?php

namespace App\Services\WooCommerce\Factories;

use App\Services\WooCommerce\DataObjects\Customer;
use App\Services\Contracts\FactoryContract;

class CustomerFactory implements FactoryContract
{
    public static function make(array $attributes): Customer
    {
        return new Customer($attributes);
    }
}
