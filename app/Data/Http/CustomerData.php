<?php

namespace App\Data\Http;

use Spatie\LaravelData\DataCollection;
use App\Data\Shared\AddressData;
use App\Data\BaseData;

class CustomerData extends BaseData {
    
    public function __construct(
        public int $customer_id,
        public ?string $date_created,
        public ?string $date_modified,
        public string $email,
        public ?string $first_name,
        public ?string $last_name,
        public string $role,
        public string $username,
        public ?AddressData $shipping,
        public ?AddressData $billing,
        public bool $is_paying_customer,
        public ?string $avatar_url,
        /** @var \App\Data\Customer\MetaData[] */
        public ?DataCollection $meta_data 
    ) {

    }

    public static function _fromResponse(array $data) : static { 
        $attributes = static::getAttributes();
        $_data = [];
        
        if ($data) {
            foreach ($data as $key => $value) {
                if ($key === 'id') {
                    $_data['customer_id'] = $value;
                }
                
                if (!in_array($key, $attributes)) {
                    continue;
                }
                
                if (is_null($value)) {
                    $value = "N/A";
                }
                
                if ($key === 'shipping' || $key === 'billing') {
                    $value = (array) $value;
                }

                if ($key === 'meta_data' && $value) {
                    $meta_data = array();
                    foreach ($data[$key] as $meta) {
                        $meta = (array) $meta;

                        if ($meta['key'] === '_stripe_customer') {
                            continue;
                        }
                        
                        $meta_data[] = $meta;
                    }

                    $value = $meta_data;
                }

                if (in_array($key, array('date_created', 'date_modified'))) {
                    $value = null;
                }

                $_data[$key] = $value;
            }
        }

        return static::from($_data);
    }
}