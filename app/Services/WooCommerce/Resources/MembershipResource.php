<?php

namespace App\Services\WooCommerce\Resources;

use App\Services\Contracts\ResourceContract;
use App\Services\WooCommerce\Factories\MembershipFactory;
use App\Services\Resources\BaseResource;

class MembershipResource extends BaseResource implements ResourceContract
{
    /**
     * Sometimes the endpoint is different that the
     * Resource name.
     *
     * So, let's add a slug to prevent it
     *
     * @var string|null
     */
    public string|null $slug = 'memberships';

    /**
     * Endpoint Name
     *
     * @var string
     */
    public string $endpoint = 'kindhumans-memberships';

    /**
     * Order Factory
     *
     * @var string
     */
    public string $factory = MembershipFactory::class;
}
