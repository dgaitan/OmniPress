<?php

namespace App\Services;

use Exception;
use App\Models\Sync;
use App\Servies\WooCommerce\WooCommerceService;
use App\Services\Contracts\ServiceContract;

class SyncService {

    /**
     * Availiable Services
     *
     * @var array
     */
    protected $services = [
        'woocommerce' => WooCommerceService::class
    ];

    /**
     * Current Service
     *
     * @var ServiceContract
     */
    protected ServiceContract $service;

    /**
     * Load the service
     *
     * @param string $service
     */
    public function __construct(string $service)
    {
        if (! in_array($service, array_keys($this->services))) {
            throw new Exception("Service Does Not Exists");
        }

        $this->service = resolve($this->services[$service]);
    }

    /**
     * Retrieve Service
     *
     * @return ServiceContract
     */
    public function getService(): ServiceContract
    {
        return $this->service;
    }

    public function find(string $resource, int $id) {

    }

    // protected function initializeSync(string $resource) {
    //     return Sync::initialize($resource, )
    // }

    /**
     * Build a new service
     *
     * @param string $service
     * @return void
     */
    public static function make(string $service) {
        return new self($service);
    }
}
