<?php

namespace App\Services\WooCommerce\Resources;

use App\Services\Contracts\ResourceContract;
use App\Services\Resources\BaseResource;
use App\Services\WooCommerce\Factories\OrderFactory;

class OrderResource extends BaseResource implements ResourceContract
{
    /**
     * Endpoint Name
     *
     * @var string
     */
    public string $endpoint = 'orders';

    /**
     * Order Factory
     *
     * @var string
     */
    public string $factory = OrderFactory::class;
}
