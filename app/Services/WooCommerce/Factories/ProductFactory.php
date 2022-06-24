<?php

namespace App\Services\WooCommerce\Factories;

use App\Services\Contracts\FactoryContract;
use App\Services\WooCommerce\DataObjects\Product;

class ProductFactory implements FactoryContract
{
    public static function make(array $attributes): Product
    {
        return new Product($attributes);
    }
}
