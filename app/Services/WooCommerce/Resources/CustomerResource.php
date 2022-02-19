<?php

namespace App\Services\WooCommerce\Resources;

use App\Services\Contracts\ResourceContract;
use App\Services\WooCommerce\Factories\CustomerFactory;
use App\Services\Resources\BaseResource;

class CustomerResource extends BaseResource implements ResourceContract
{
    /**
     * Endpoint Name
     *
     * @var string
     */
    public string $endpoint = 'customers';

    /**
     * Order Factory
     *
     * @var string
     */
    public string $factory = CustomerFactory::class;

    /**
     * Used to send custom params
     *
     * @return array
     */
    protected function requestParams(): array {
        return [
            'role' => 'all'
        ];
    }
}
