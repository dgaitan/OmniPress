<?php

namespace App\Services\WooCommerce\Resources;

use App\Services\Contracts\ResourceContract;
use App\Services\Resources\BaseResource;
use App\Services\WooCommerce\Factories\ProductFactory;

class ProductResource extends BaseResource implements ResourceContract
{
    /**
     * Endpoint Name
     *
     * @var string
     */
    public string $endpoint = 'products';

    /**
     * Product Factory
     *
     * @var string
     */
    public string $factory = ProductFactory::class;
}
