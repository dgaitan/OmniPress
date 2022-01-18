<?php

namespace App\Http\Clients;

use Automattic\WooCommerce\Client as WooCommerce;

class Client {
    protected $domain;
    protected $secret;
    protected $key;
    protected $api;

    public function __construct() {
        $this->setCredentials();
        $this->loadApi();
    }

    protected function setCredentials() {
        $this->domain = env('WOO_CUSTOMER_DOMAIN');
        $this->secret = env('WOO_CUSTOMER_SECRET');
        $this->key = env('WOO_CUSTOMER_KEY');
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

