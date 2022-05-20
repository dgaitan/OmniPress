<?php

namespace App\Services\WooCommerce\Resources;

use App\Services\Contracts\ResourceContract;
use App\Services\WooCommerce\Factories\IndexFactory;
use App\Services\Resources\BaseResource;

class IndexResource extends BaseResource implements ResourceContract
{
    /**
     * Endpoint Name
     *
     * @var string
     */
    public string $endpoint = 'kinja-indexes';

    /**
     * Order Factory
     *
     * @var string
     */
    public string $factory = IndexFactory::class;
}
