<?php

namespace App\Http\Client;

use Automattic\WooCommerce\Client as WooCommerce;

abstract class BaseClient {

    protected $api;
    protected $endpoint;
    protected $params = [];
    protected $results;

    public function __construct(WooCommerce $api) {
        $this->api = $api;
    }

    abstract protected function getDataProcessed();

    public function get() {
        $params = $this->params;
        $params['per_page'] = 100;
        $params['page'] = 1;
        $hasResults = true;

        while ($hasResults) {
            $results = $this->api->get($this->endpoint, $params);

            if (!$results) {
                $hasResults = false;
                break;
            }
    
            $this->results = $this->results + $results;
        }

    }
}