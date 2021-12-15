<?php

namespace App\Data\Http;

use Spatie\LaravelData\DataCollection;
use App\Data\Coupon\CouponSettingData;

class CouponData extends BaseHttpData {
    public static $id_field = 'coupon_id';
    
    public function __construct(
        public int $coupon_id,
        public string $date_created,
        public string $date_expires,
        public string $date_modified,
        public bool $individual_use,
        public int $amount,
        public string $description,
        public string $discount_type,
        public CouponSettingData $settings,
        /** @var \App\Data\Coupon\CouponMetaData[] */
        public ?DataCollection $meta_data 
    ) {

    }
}