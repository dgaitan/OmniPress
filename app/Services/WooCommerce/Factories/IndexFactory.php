<?php

namespace App\Services\WooCommerce\Factories;

use App\Services\WooCommerce\DataObjects\Index;
use App\Services\Contracts\FactoryContract;

class IndexFactory implements FactoryContract
{
    public static function make(array $attributes): Index
    {
        return new Index($attributes);
    }
}
