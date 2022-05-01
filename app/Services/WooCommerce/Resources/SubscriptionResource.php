<?php

namespace App\Services\WooCommerce\Resources;

use App\Services\Contracts\ResourceContract;
use App\Services\WooCommerce\Factories\SubscriptionFactory;
use App\Services\Resources\BaseResource;

class SubscriptionResource extends BaseResource implements ResourceContract
{
    /**
     * Sometimes the endpoint is different that the
     * Resource name.
     *
     * So, let's add a slug to prevent it
     *
     * @var string|null
     */
    public string|null $slug = 'subscriptions';

    /**
     * Endpoint Name
     *
     * @var string
     */
    public string $endpoint = 'kindhumans-subscriptions';

    /**
     * Order Factory
     *
     * @var string
     */
    public string $factory = SubscriptionFactory::class;

    public function syncAll(int|null $perPage, int $page = 1, int $sync_id = 0): void {
        if (! $perPage) {
            $perPage = env('KINDHUMANS_SYNC_PER_PAGE', 100);
        }

        $params = ['per_page' => $perPage, 'page' => $page];
        $response = $this->all($params);
        
        $response->map(fn($item) => $item->sync());
    }
}
