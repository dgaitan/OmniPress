<?php

namespace App\Services\WooCommerce\Factories;

use App\Services\WooCommerce\DataObjects\Cause;
use App\Services\Contracts\FactoryContract;

class CauseFactory implements FactoryContract
{
    public static function make(array $attributes): Cause
    {
        return new Cause($attributes);
    }
}
