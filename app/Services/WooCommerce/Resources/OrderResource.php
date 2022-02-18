<?php

namespace App\Services\WooCommerce\Resources;

use App\Services\Contracts\ResourceContract;
use App\Services\Contracts\ServiceContract;
use App\Services\WooCommerce\Factories\OrderFactory;
use App\Services\WooCommerce\DataObjects\Order;
use Illuminate\Support\Collection;

class OrderResource implements ResourceContract
{
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
     * Undocumented function
     *
     * @return void
     */
    public function all(array $params = []): Collection {
        if (!isset($params['per_page'])) {
            $params['per_page'] = 100;
        }

        $api = $this->service->makeRequest();
        $response = $api->get('orders', $params);

        if (!$response) {

        }

        return collect($response)->map(fn(object $order) => OrderFactory::make(
            attributes: (array) $order
        ));
    }

    public function find(int $order_id): Order {
        $api = $this->service->makeRequest();
        $response = $api->get(sprintf('orders/%s', $order_id));

        return OrderFactory::make(attributes: (array) $response);
    }
}
