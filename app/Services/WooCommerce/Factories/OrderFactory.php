<?php

namespace App\Services\WooCommerce\Factories;

use App\Services\WooCommerce\DataObjects\Order;
use App\Services\Contracts\FactoryContract;

class OrderFactory implements FactoryContract
{
    public static function make(array $attributes): Order
    {
        return new Order($attributes);
    }
}
