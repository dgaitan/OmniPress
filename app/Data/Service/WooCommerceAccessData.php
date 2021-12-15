<?php

namespace App\Data\Service;

class WooCommerceAccessData extends BaseServiceAccessData {
    
    public function __construct(
        public string $domain,
        public string $customer_secret,
        public string $customer_key
    ) {

    }
}