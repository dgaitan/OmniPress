<?php

namespace App\Services\WooCommerce\Resources;

use App\Models\Membership;
use App\Services\Contracts\ResourceContract;
use App\Services\Resources\BaseResource;
use App\Services\WooCommerce\Factories\MembershipFactory;
use Illuminate\Http\Client\Response;
use InvalidArgumentException;

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

    /**
     * Update the client kindcash in Kindhumans store.
     *
     * @param integer|Membership $membership
     * @return Response
     */
    public function updateClientKindCash(int|Membership $membership): Response
    {
        if (! $membership instanceof Membership) {
            $membership = Membership::find($membership);
        }

        if (is_null($membership)) {
            throw new InvalidArgumentException(
                sprintf('Invalid Membership to update client kind cash on: %s. Integer or a Membership is needed.', self::class)
            );
        }

        return $this->service->post(
            sprintf(
                '%s/%s/update-kind-cash',
                $this->endpoint,
                $membership->id,
            ),
            [
                'customer_id' => $membership->customer->customer_id,
                'kind_cash' => $membership->kindCash->points
            ]
        );
    }
}
