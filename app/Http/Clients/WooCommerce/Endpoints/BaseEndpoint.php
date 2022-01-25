<?php

namespace App\Http\Clients\WooCommerce\Endpoints;

use Automattic\WooCommerce\Client as WooCommerce;
use App\Helpers\API\Testeable;
use Exception;

abstract class BaseEndpoint {

    use Testeable;

    /**
     * WooCommerce api instnace
     * 
     * @var WooCommerce
     */
    protected $api;
    
    /**
     * Endpoint where it should goes to
     * 
     * @var string
     */
    protected $endpoint;

    /**
     * Constructor
     */
    public function __construct(WooCommerce $api) {
        $this->api = $api;
    }

    /**
     * Data processor.
     * 
     * Should be an instance of Data
     */
    abstract protected function getDataProcessor();

    /**
     * Get Data
     * 
     * @var array $params
     * @return array
     */
    public function get(array $params = array()): array {
        $results = array();
        $params = $this->getParams($params);

        if ($this->isTesting && !$this->retrieveFromAPI) {
            $response = $this->testingData;
            $results[1] = $this->getDataProcessor()::collectFromResponse($response);

            return $results;
        }

        // When a 'take' param is present on params
        // it means that we only want that quantity of results.
        if (isset($params['take'])) {
            $params['per_page'] = $params['take'];

            // Make the request
            $response = $this->api->get($this->endpoint, $params);
            // Add it like page one only
            $results[1] = $this->getDataProcessor()::collectFromResponse($response);
        } else {
            $hasResults = true; // This helps to know if still we need to loop
            $page = $params['page']; // Store the current page to autoincremente later

            // While still we have results. lets loop on coming pages
            while ($hasResults) {
                $params['page'] = $page;
                $response = $this->api->get($this->endpoint, $params);
                
                // If the response returns an empty array.
                // it means we should stop the loop
                if (!$response) {
                    $hasResults = false;
                    break;
                }

                $results[$page] = $this->getDataProcessor()::collectFromResponse($response);
                $page++;
            }

        }

        return $results;
    }

    /**
     * Get params ready to go
     * 
     * @var array $param
     * @return array
     */
    protected function getParams(array $params): array {
        if (!isset($params['per_page'])) {
            $params['per_page'] = 100;
        }

        if (!isset($params['page'])) {
            $params['page'] = 1;
        }

        if (!isset($params['role'])) {
            $params['role'] = 'all';
        }

        return $params;
    }
}