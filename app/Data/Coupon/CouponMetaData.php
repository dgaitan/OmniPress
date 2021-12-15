<?php

namespace App\Data\Coupon;

use Spatie\LaravelData\Data;

class CouponMetaData extends Data {
    
    public function __construct(
        public int $id,
        public string $key,
        public mixed $value
    ) {

    }
}