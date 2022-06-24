<?php

namespace App\Services\WooCommerce\Factories;

use App\Services\Contracts\FactoryContract;
use App\Services\WooCommerce\DataObjects\Order;

class OrderFactory implements FactoryContract
{
    public static function make(array $attributes): Order
    {
        return new Order($attributes);
    }
}
