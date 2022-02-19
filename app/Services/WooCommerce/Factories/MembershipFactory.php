<?php

namespace App\Services\WooCommerce\Factories;

use App\Services\WooCommerce\DataObjects\Membership;
use App\Services\Contracts\FactoryContract;

class MembershipFactory implements FactoryContract
{
    public static function make(array $attributes): Membership
    {
        return new Membership($attributes);
    }
}
