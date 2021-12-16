<?php

namespace App\Http\Clients\WooCommerce\Endpoints;

use App\Data\Http\CouponData;

class CouponEndpoint extends BaseEndpoint {
    
    /**
     * Endpoint where it should goes to
     * 
     * @var string
     */
    protected $endpoint = 'coupons';

    /**
     * Data processor.
     * 
     * Should be an instance of Data
     */
    protected function getDataProcessor() {
        return CouponData::class;
    }
}