<?php

namespace App\Http\Clients;

use Automattic\WooCommerce\Client as WooCommerce;

class Client {
    protected $domain;
    protected $secret;
    protected $key;
    protected $service;
    protected $api;

    public function __construct(\App\Models\Service $service) {
        $this->service = $service;
        $this->setCredentials();
        $this->loadApi();
    }

    public static function initialize(\App\Models\Service $service) {
        return new self($service);
    }

    protected function setCredentials() {
        $this->domain = $this->service->access->domain;
        $this->secret = $this->service->access->customer_secret;
        $this->key = $this->service->access->customer_key;
    }

    protected function loadApi() {
        $this->api = new WooCommerce(
            $this->domain,
            $this->key,
            $this->secret,
            [
                'version' => 'wc/v3',
                'user_agent' => 'woo_omni_agent_client'
            ]
        );
    }

    public function getApi() {
        return $this->api;
    }
}

