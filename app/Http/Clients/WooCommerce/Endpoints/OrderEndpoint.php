<?php

namespace App\Http\Clients\WooCommerce\Endpoints;

use App\Data\Http\OrderData;

class OrderEndpoint extends BaseEndpoint {
    
    /**
     * Endpoint where it should goes to
     * 
     * @var string
     */
    protected $endpoint = 'orders';

    /**
     * Data processor.
     * 
     * Should be an instance of Data
     */
    protected function getDataProcessor() {
        return OrderData::class;
    }
}