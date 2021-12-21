<?php

namespace App\Http\Clients\WooCommerce;

use App\Data\Http\CustomerData;
use App\Http\Clients\WooCommerce\Endpoints\CustomerEndpoint;
use App\Http\Clients\WooCommerce\Endpoints\CouponEndpoint;
use App\Http\Clients\WooCommerce\Endpoints\OrderEndpoint;

class WooCommerceClient {

    /**
     * The Client
     * 
     * @var \App\Http\Clients\Client;
     */
    protected $client;

    protected $endpoints;

    /**
     * Is the client in testing mode?
     * 
     * @var bool
     */
    protected $isTesting = false;
    protected $testingData = [];

    public function __construct(\App\Http\Clients\Client $client) {
        $this->client = $client;
        $this->loadEndpoints();
    }

    /**
     * WOoCOmmerce Endpoints Requests
     * 
     * @return array
     */
    public function getEndpoints(): array {
        return [
            'customers' => CustomerEndpoint::class,
            'coupons' => CouponEndpoint::class,
            'orders' => OrderEndpoint::class
        ];
    }

    /**
     * Load the enpoints registered
     * 
     * @return void
     */
    public function loadEndpoints() : void {
        $endpoints = $this->getEndpoints();

        if ($endpoints) {
            foreach ($endpoints as $name => $endpoint) {
                $this->endpoints[$name] = new $endpoint($this->client->getApi());
            }
        }
    }

    public function setTestingMode(bool $isTesting): WooCommerceClient {
        $this->isTesting = $isTesting;
        return $this;
    }

    /**
     * Set testing data
     * 
     * it should follows the next format:
     * 
     * [
     *  'customers' => ...,
     *  'coupons' => ...,
     * ]
     * 
     * @param array $data
     * @return WooCoomerceClient
     */
    public function setTestingData(array $data): WooCommerceClient {
        $this->testingData = $data;
        return $this;
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
            $this->endpoints[$methodName]
                ->setTestingMode($this->isTesting)
                ->setTestingData($this->testingData[$methodName]);
            
            return $this->endpoints[$methodName]->get(...$params);
        }

        if (!array_key_exists($methodName, get_class_methods($this))) {
			return null;
		}

        
        return $this->{$methodName}(...$params);
    }
}