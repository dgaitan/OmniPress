<?php

namespace App\Services\Resources;

use App\Services\Contracts\DataObjectContract;
use App\Services\Contracts\ServiceContract;
use App\Services\Contracts\FactoryContract;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseResource {
    /**
     * Resource Endpoint
     *
     * @var string
     */
    public string $endpoint = '';

    /**
     * Factory
     *
     * @var FactoryContract
     */
    public string $factory;

    /**
     * A Resource should receive a Service
     *
     * @param ServiceContract $service
     */
    public function __construct(
        private ServiceContract $service,
    ) {}

    /**
     * Retrieve Service
     *
     * @return ServiceContract
     */
    public function service(): ServiceContract
    {
        return $this->service;
    }

    /**
     * Collect All Items
     *
     * @return Collection
     */
    public function all(array $params = []): Collection|bool {
        if (!isset($params['per_page'])) {
            $params['per_page'] = 100;
        }

        $api = $this->service->makeRequest();
        $response = $api->get($this->endpoint, $params);

        if (!$response) {
            return false;
        }

        return collect($response)->map(fn(object $item) => $this->factory::make(
            attributes: (array) $item
        ));
    }

    /**
     * Find a
     *
     * @param integer $order_id
     * @return DataObjectContract
     */
    public function find(int $id): DataObjectContract {
        $api = $this->service->makeRequest();
        $response = $api->get(sprintf('%s/%s', $this->endpoint, $id));

        return $this->factory::make(attributes: (array) $response);
    }

    /**
     * Get all and sync.
     *
     * Basically Sync all elements
     *
     * @param integer $per_page
     * @return void
     */
    public function syncAll(int|null $perPage, int $page = 1): void {
        if (! $perPage) {
            $perPage = env('KINDHUMANS_SYNC_PER_PAGE', 100);
        }

        $params = ['per_page' => $perPage, 'page' => $page];
        $response = $this->all($params);

        if ($response) {
            $response->map(fn($item) => $item->sync());
            $page++;
            \App\Jobs\WooCommerceSyncServiceJob::dispatch($this->endpoint, $perPage, $page);
        }
    }

    /**
     * Find an object and sync it
     *
     * @param integer $id
     * @return Model|bool
     */
    public function findAndSync(int $id): Model|bool {
        $element = $this->find($id);

        if ($element) {
            return $element->sync();
        }

        return false;
    }

    /**
     * Create an element or object
     *
     * @param DataObjectContract $dataObject
     * @return DataObjectContract|Model|null
     */
    public function create(
        DataObjectContract $dataObject,
        bool $sync = false
    ): DataObjectContract|Model|null {
        $api = $this->service->makeRequest();
        $response = $api->post($this->endpoint, $dataObject->toArray());

        if ($response) {
            $dataObject = $this->factory::make(attributes: (array) $response);

            if ($sync) {
                return $dataObject->sync();
            }

            return $dataObject;
        }

        return null;
    }
}
