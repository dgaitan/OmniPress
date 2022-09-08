<?php

namespace App\Services\WooCommerce\Resources;

use App\Services\Contracts\ResourceContract;
use App\Services\Resources\BaseResource;
use App\Services\WooCommerce\Factories\MembershipFactory;

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

    /**
     * Run Add Gift PRoduct To ORder
     *
     * @param  int  $order_id
     * @return object|null|false
     */
    public function addGiftProduct(int $order_id)
    {
        return $this->service->put(
            sprintf('%s/%s/set-gift', $this->endpoint, $order_id),
            []
        );
    }
}
