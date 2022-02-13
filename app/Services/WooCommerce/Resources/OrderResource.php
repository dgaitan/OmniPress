<?php

namespace App\Services\WooCommerce\Resources;

use App\Services\Contracts\ResourceContract;
use App\Services\Contracts\ServiceContract;

class OrderResource implements ResourceContract
{
    /**
     * A Resource should receive a Service
     *
     * @param ServiceContract $service
     */
    public function __construct(
        private ServiceContract $service,
    ) {}

    public function service(): ServiceContract
    {
        return $this->service;
    }

    public function all() {
        
    }
}