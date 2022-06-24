<?php

namespace App\Services\WooCommerce\Factories;

use App\Services\Contracts\FactoryContract;
use App\Services\WooCommerce\DataObjects\Customer;

class CustomerFactory implements FactoryContract
{
    public static function make(array $attributes): Customer
    {
        return new Customer($attributes);
    }
}
