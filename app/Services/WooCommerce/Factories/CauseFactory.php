<?php

namespace App\Services\WooCommerce\Factories;

use App\Services\Contracts\FactoryContract;
use App\Services\WooCommerce\DataObjects\Cause;

class CauseFactory implements FactoryContract
{
    public static function make(array $attributes): Cause
    {
        return new Cause($attributes);
    }
}
