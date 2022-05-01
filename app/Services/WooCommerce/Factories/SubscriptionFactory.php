<?php

namespace App\Services\WooCommerce\Factories;

use App\Services\WooCommerce\DataObjects\Subscription;
use App\Services\Contracts\FactoryContract;

class SubscriptionFactory implements FactoryContract
{
    public static function make(array $attributes): Subscription
    {
        return new Subscription($attributes);
    }
}
