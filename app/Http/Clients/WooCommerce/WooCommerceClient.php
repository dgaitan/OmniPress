<?php

namespace App\Http\Clients\WooCommerce;

use App\Http\Clients\WooCommerce\Endpoints\CustomerEndpoint;
use App\Http\Clients\WooCommerce\Endpoints\CouponEndpoint;
use App\Http\Clients\WooCommerce\Endpoints\OrderEndpoint;
use App\Http\Clients\WooCommerce\Endpoints\ProductEndpoint;
use App\Helpers\API\Testeable;

class WooCommerceClient {

    use Testeable;

    /**
     * The Client
     * 
     * @var \App\Http\Clients\Client;
     */
    protected $client;

    /**
     * The endpoints handlers
     * 
     * @var array
     */
    protected $endpoints;

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
            'orders' => OrderEndpoint::class,
            'products' => ProductEndpoint::class
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
            // Do this only is testing mode
            if ($this->isTesting) {
                $this->endpoints[$methodName]
                    ->setTestingMode($this->isTesting)
                    ->setTestingData($this->testingCollectionData[$methodName])
                    ->retrieveDataFromAPI($this->retrieveFromAPI);
            }
            
            return $this->endpoints[$methodName]->get(...$params);
        }

        if (!array_key_exists($methodName, get_class_methods($this))) {
			return null;
		}

        
        return $this->{$methodName}(...$params);
    }
}