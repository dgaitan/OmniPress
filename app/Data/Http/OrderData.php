<?php

namespace App\Data\Http;

use Spatie\LaravelData\DataCollection;
use App\Data\BaseData;
use App\Data\Shared\AddressData;

class OrderData extends BaseData {
    public static $id_field = 'coupon_id';
    
    public function __construct(
        public int $order_id,
        public int $parent_id,
        public int $number,
        public string $order_key,
        public string $created_via,
        public string $version,
        public string $status,
        public string $currency,
        public string $date_created,
        public string $date_modified,
        public int $discount_total,
        public int $discount_tax,
        public int $shipping_total,
        public int $shipping_tax,
        public int $cart_tax,
        public int $total,
        public int $total_tax,
        public bool $prices_includes_tax,
        public int $customer_id,
        public string $customer_ip_address,
        public string $customer_user_age,
        public string $customer_note,
        public AddressData $billing,
        public AddressData $shipping,
        public string $payment_method,
        public string $transaction_id,
        public string $date_paid,
        public string $date_completed,
        public string $cart_hash,
        public bool $set_paid,
        /** @var \App\Data\Coupon\CouponMetaData[] */
        public ?DataCollection $meta_data 
    ) {

    }

    public static function _fromResponse(array $data) : static { 
        $attributes = static::getAttributes();
        $_data = [];
        
        if ($data) {
            foreach ($data as $key => $value) {
                if ($key === 'id') {
                    $_data[static::$id_field] = $value;
                }
                
                if (!in_array($key, $attributes)) {
                    continue;
                }
                
                if (is_null($value)) {
                    $value = "N/A";
                }

                if ($key === 'meta_data' && $value) {
                    $meta_data = array();
                    foreach ($data[$key] as $meta) {
                        $meta_data[] = (array) $meta;
                    }

                    $value = $meta_data;
                }

                $_data[$key] = $value;
            }
        }

        $_data['settings'] = CouponSettingData::_fromResponse($data);

        return static::from($_data);
    }
}