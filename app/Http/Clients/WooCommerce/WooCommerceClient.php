<?php

namespace App\Http\Clients\WooCommerce;

use App\Data\Http\CustomerData;
use App\Http\Clients\WooCommerce\Endpoints\CustomerEndpoint;
use App\Http\Clients\WooCommerce\Endpoints\CouponEndpoint;

class WooCommerceClient {

    /**
     * The Client
     * 
     * @var \App\Http\Clients\Client;
     */
    protected $client;

    protected $endpoints;

    public function __construct(\App\Http\Clients\Client $client) {
        $this->client = $client;
        $this->loadEndpoints();
    }

    public function getEndpoints() {
        return [
            'customers' => CustomerEndpoint::class,
            'coupons' => CouponEndpoint::class
        ];
    }
    
    public function loadEndpoints() : void {
        $endpoints = $this->getEndpoints();

        if ($endpoints) {
            foreach ($endpoints as $name => $endpoint) {
                $this->endpoints[$name] = new $endpoint($this->client->getApi());
            }
        }
    }

    /**
     * Check 
     */
    public function __call(string $name, array $params) {
        $methodName = explode('get', $name);

        if (count($methodName) === 1) {
            return null;
        }

        $methodName = end($methodName);
        $methodName = str_split($methodName);
        $methodName[0] = strtolower($methodName[0]);
        $methodName = implode('', $methodName);
        if (array_key_exists($methodName, $this->endpoints)) {
            return $this->endpoints[$methodName]->get(...$params);
        }

        if (!array_key_exists($methodName, get_class_methods($this))) {
			return null;
		}

        return $this->{$methodName}(...$params);
    }
}