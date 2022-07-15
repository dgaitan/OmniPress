<?php

namespace App\Services\WooCommerce;

use App\Services\Concerns\CanBeFaked;
use App\Services\Contracts\ResourceContract;
use App\Services\Contracts\ServiceContract;
use App\Services\WooCommerce\Resources\CauseResource;
use App\Services\WooCommerce\Resources\CustomerResource;
use App\Services\WooCommerce\Resources\MembershipResource;
use App\Services\WooCommerce\Resources\OrderResource;
use App\Services\WooCommerce\Resources\PaymentMethodResource;
use App\Services\WooCommerce\Resources\ProductResource;
use Automattic\WooCommerce\Client as WooCommerce;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class WooCommerceService implements ServiceContract
{
    use CanBeFaked;

    /**
     * Service Constructor
     *
     * @param  string  $domain
     * @param  string  $key
     * @param  string  $secret
     */
    public function __construct(
        public string $domain,
        public string $key,
        public string $secret,
    ) {
    }

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
                'user_agent' => 'KinjaOmniClient',
            ]
        );

        return $request;
    }

    /**
     * Make a Request using Laravel Http
     *
     * @return PendingRequest
     */
    public function request(): PendingRequest
    {
        $request = Http::withBasicAuth($this->key, $this->secret)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'KinjaOmniClient/3',
            ])->connectTimeout(20);

        return $request;
    }

    /**
     * Get Endpoint url
     *
     * @param  string  $endpoint
     * @return string
     */
    public function getEndpointUrl(string $endpoint): string
    {
        return sprintf('%s/wp-json/wc/v3/%s', $this->domain, $endpoint);
    }

    /**
     * Get an element
     *
     * @param  string  $endpoint
     * @return Response
     */
    public function get(string $endpoint, array $query = []): Response
    {
        return $this->request()->get(
            url: $this->getEndpointUrl(endpoint: $endpoint),
            query: $query
        );
    }

    /**
     * Post Request
     *
     * @param  string  $endpoint
     * @param  array  $data
     * @return Response
     */
    public function post(string $endpoint, array $data = []): Response
    {
        return $this->request()->post(
            url: $this->getEndpointUrl(endpoint: $endpoint),
            data: $data
        );
    }

    /**
     * Put Request
     *
     * @param  string  $endpoint
     * @param  array  $data
     * @return Response
     */
    public function put(string $endpoint, array $data = []): Response
    {
        return $this->request()->put(
            url: $this->getEndpointUrl(endpoint: $endpoint),
            data: $data
        );
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
     * Membership Resource
     *
     * @return MembershipResource
     */
    public function memberships(): MembershipResource
    {
        return new MembershipResource(service: $this);
    }

    /**
     * Payment Methods
     *
     * @return PaymentMethodResource
     */
    public function paymentMethods(): PaymentMethodResource
    {
        return new PaymentMethodResource(service: $this);
    }

    /**
     * Causes
     *
     * @return CauseResource
     */
    public function causes(): CauseResource
    {
        return new CauseResource(service: $this);
    }

    /**
     * Resolve Service
     *
     * @return ServiceContract
     */
    public static function make(): ServiceContract
    {
        return resolve(WooCommerceService::class);
    }
}
