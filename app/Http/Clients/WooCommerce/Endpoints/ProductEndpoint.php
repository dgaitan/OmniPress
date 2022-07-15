<?php

namespace App\Http\Clients\WooCommerce\Endpoints;

use App\Data\Http\ProductData;

class ProductEndpoint extends BaseEndpoint
{
    /**
     * Endpoint where it should goes to
     *
     * @var string
     */
    protected $endpoint = 'products';

    /**
     * Data processor.
     *
     * Should be an instance of Data
     */
    protected function getDataProcessor()
    {
        return ProductData::class;
    }
}
