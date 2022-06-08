<?php

namespace App\Services\WooCommerce\Resources;

use App\Services\Contracts\ResourceContract;
use App\Services\WooCommerce\Factories\CauseFactory;
use App\Services\Resources\BaseResource;

class CauseResource extends BaseResource implements ResourceContract
{
    /**
     * Endpoint Name
     *
     * @var string
     */
    public string $endpoint = 'causes';

    /**
     * Order Factory
     *
     * @var string
     */
    public string $factory = CauseFactory::class;

    /**
     * Get all and sync.
     *
     * Basically Sync all elements
     *
     * @param integer $per_page
     * @return void
     */
    public function syncAll(int|null $perPage, int $page = 1, int $sync_id = 0): void {
        $params = array_merge(['per_page' => $perPage, 'page' => $page], $this->requestParams());

        $response = $this->all($params);

        if ($response) {
            $response->map(fn($item) => $item->sync());
        }
    }
}
