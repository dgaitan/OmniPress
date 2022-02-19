<?php

namespace App\Services\WooCommerce;

use App\Services\Concerns\CanBeFaked;
use App\Services\Contracts\ServiceContract;
use App\Services\Contracts\ResourceContract;
use App\Services\WooCommerce\Resources\OrderResource;
use App\Services\WooCommerce\Resources\CustomerResource;
use App\Services\WooCommerce\Resources\ProductResource;
use Automattic\WooCommerce\Client as WooCommerce;

class WooCommerceService implements ServiceContract
{
    use CanBeFaked;

    /**
     * Service Constructor
     *
     * @param string $domain
     * @param string $key
     * @param string $secret
     */
    public function __construct(
        public string $domain,
        public string $key,
        public string $secret,
    ) {}

    /**
     * Create a new WooCommerce Request
     *
     * @return WooCommerce
     */
    public function makeRequest(): WooCommerce
    {
        $request = new WooCommerce(
            $this->domain,
            $this->key,
            $this->secret,
            [
                'timeout' => env('WOO_TIMEOUT', 20),
                'user_agent' => 'KinjaOmniClient'
            ]
        );

        return $request;
    }

    /**
     * Order Resource
     *
     * @return ResourceContract
     */
    public function orders(): ResourceContract
    {
        return new OrderResource(service: $this);
    }

    /**
     * Customer Resource
     *
     * @return CustomerResource
     */
    public function customers(): CustomerResource
    {
        return new CustomerResource(service: $this);
    }

    /**
     * Product Resource
     *
     * @return ProductResource
     */
    public function products(): ProductResource
    {
        return new ProductResource(service: $this);
    }

    /**
     * Resolve Service
     *
     * @return ServiceContract
     */
    public static function make(): ServiceContract {
        return resolve(WooCommerceService::class);
    }
}
