<?php

namespace App\Services\Printforia;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class PrintforiaApiClient {

    /**
     * Printforia API Key
     *
     * @var string
     */
    protected string $apiKey;

    /**
     * Printforia API URL.
     *
     * @var string
     */
    protected string $apiUrl;

    /**
     * INitialize Printforia Apli Client
     */
    public function __construct() {
        $this->apiKey = env('PRINTFORIA_API_KEY', '');
        $this->apiUrl = env('PRINTFORIA_API_URL', 'https://api-sandbox.printforia.com/v2/');

        if (empty($this->apiKey)) {
            throw new InvalidArgumentException(
                'Printforia Api Key is required, please check that PRINTFORIA_API_KEY exists in your .env file'
            );
        }
    }

    /**
     * GEt a simple order
     *
     * @param string $orderId
     * @return void
     */
    public function getOrder(string $orderId) {
        return $this->request()->get(
            $this->getApiUrl(sprintf('orders/%s', $orderId))
        );
    }

    /**
     * GEt Api Url
     *
     * @param string $endpoint
     * @return string
     */
    public function getApiUrl(string $endpoint): string {
        return sprintf(
            '%s%s',
            $this->apiUrl,
            $endpoint
        );
    }

    /**
     * INitialize Api Request
     *
     */
    public function request() {
        return Http::withHeaders([
            'X-Token' => $this->apiKey,
            'Content-Type' => 'application/json'
        ]);
    }
}
