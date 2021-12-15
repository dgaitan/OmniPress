<?php

namespace App\Http\Clients\WooCommerce;

use App\Data\Http\CustomerData;

class WooCommerceClient {

    protected $client;

    public function __construct(\App\Http\Clients\Client $client) {
        $this->client = $client;
    }

    public function getCustomers(array $params = []): array {
        $results = array();
        $endpoint = 'customers';

        if (isset($params['take'])) {
            $response = $this->client->getApi()->get($endpoint, [
                'page' => 1,
                'per_page' => $params['take'],
                'role' => 'all'
            ]);

            $results[1] = CustomerData::collectFromResponse($response);
        }

        return $results;
    }
}