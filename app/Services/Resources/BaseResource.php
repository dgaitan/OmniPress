<?php

namespace App\Services\Resources;

use App\Services\Contracts\DataObjectContract;
use App\Services\Contracts\FactoryContract;
use App\Services\Contracts\ServiceContract;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

abstract class BaseResource
{
    /**
     * Sometimes the endpoint is different that the
     * Resource name.
     *
     * So, let's add a slug to prevent it
     *
     * @var string|null
     */
    public string|null $slug = null;

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
     * Service
     *
     * @var ServiceContract
     */
    private ServiceContract $service;

    /**
     * A Resource should receive a Service
     *
     * @param  ServiceContract  $service
     */
    public function __construct(ServiceContract $service)
    {
        $this->service = $service;
    }

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
    public function all(array $params = []): Collection|bool
    {
        if (! isset($params['per_page'])) {
            $params['per_page'] = 100;
        }

        $api = $this->service->makeRequest();
        $response = $api->get($this->endpoint, $params);

        if (! $response) {
            return false;
        }

        return collect($response)->map(fn (object $item) => $this->factory::make(
            attributes: (array) $item
        ));
    }

    /**
     * Find a
     *
     * @param  int  $order_id
     * @return DataObjectContract
     */
    public function find(int $id): DataObjectContract
    {
        $api = $this->service->makeRequest();
        $response = $api->get(sprintf('%s/%s', $this->endpoint, $id));

        return $this->factory::make(attributes: (array) $response);
    }

    /**
     * Get an element
     *
     * @param  int|string  $id
     * @return DataObjectContract
     */
    public function get(int|string $id): DataObjectContract|null
    {
        $response = $this->service->get(
            sprintf('%s/%s', $this->endpoint, $id)
        );

        if (! $response->ok()) {
            return null;
        }

        return $this->factory::make(attributes: (array) $response->json());
    }

    /**
     * Collect elements
     *
     * @param  array  $params
     * @return Collection|null
     */
    public function collect(array $params = []): Collection|null
    {
        if (! isset($params['per_page'])) {
            $params['per_page'] = 100;
        }

        $response = $this->service->get($this->endpoint, $params);

        if ($response->failed()) {
            return null;
        }

        return collect($response->json())->map(fn (array $item) => $this->factory::make(
            attributes: $item
        ));
    }

    /**
     * Get And Syncronize an item
     *
     * @param  int|string  $id
     * @return Model|null
     */
    public function getAndSync(int|string $id): Model|null
    {
        $order = $this->get($id);

        if (is_null($order)) {
            return null;
        }

        return $order->sync();
    }

    /**
     * Collect elements and syncronize it
     *
     * @param  array  $params
     * @return Collection|null
     */
    public function collectAndSync(array $params = []): Collection|null
    {
        $response = $this->collect(params: $params);

        if (is_null($response)) {
            return null;
        }

        return $response->map(fn ($item) => $item->sync());
    }

    /**
     * Get all and sync.
     *
     * Basically Sync all elements
     *
     * @param  int  $per_page
     * @return void
     */
    public function syncAll(int|null $perPage, int $page = 1, int $sync_id = 0): void
    {
        if (! $perPage) {
            $perPage = env('KINDHUMANS_SYNC_PER_PAGE', 100);
        }

        $sync = \App\Models\Sync::find($sync_id);
        $params = array_merge(['per_page' => $perPage, 'page' => $page], $this->requestParams());

        if ($sync->isCompleted()) {
            return;
        }

        try {
            $response = $this->all($params);

            if ($response) {
                $response->map(fn ($item) => $item->sync());
                $sync->current_page = $page + 1;
                $sync->save();
                \App\Jobs\WooCommerceSyncServiceJob::dispatch($sync->id);
            } else {
                Cache::forget('dashboard_stats');
                $sync->complete();
            }
        } catch (Exception $e) {
            $sync->status = \App\Models\Sync::FAILED;
            $sync->save();
            $sync->add_log(sprintf('Error: %s', $e->getMessage()));
        }
    }

    /**
     * Used to send custom params
     *
     * @return array
     */
    protected function requestParams(): array
    {
        return [];
    }

    /**
     * Find an object and sync it
     *
     * @param  int  $id
     * @return Model|bool
     */
    public function findAndSync(int $id): Model|bool
    {
        $element = $this->find($id);

        if ($element) {
            Cache::forget('dashboard_stats');

            return $element->sync();
        }

        return false;
    }

    /**
     * Create an element or object
     *
     * @param  DataObjectContract  $dataObject
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

    public function update(
        int|string $element_id,
        array $params,
        bool $sync = false
    ): DataObjectContract|Model|bool {
        $api = $this->service->makeRequest();
        $response = $api->put(sprintf('%s/%s', $this->endpoint, $element_id), $params);

        if ($response) {
            $dataObject = $this->factory::make(attributes: (array) $response);

            if ($sync) {
                return $dataObject->sync();
            }

            return $dataObject;
        }

        return false;
    }
}
