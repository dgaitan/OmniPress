<?php

namespace App\Http\Clients\WooCommerce\Endpoints;

use App\Data\Http\CustomerData;

class CustomerEndpoint extends BaseEndpoint
{
    /**
     * Endpoint where it should goes to
     *
     * @var string
     */
    protected $endpoint = 'customers';

    /**
     * Data processor.
     *
     * Should be an instance of Data
     */
    protected function getDataProcessor()
    {
        return CustomerData::class;
    }
}
