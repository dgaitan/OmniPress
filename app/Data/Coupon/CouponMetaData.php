<?php

namespace App\Data\Coupon;

use App\Data\BaseData;

class CouponMetaData extends BaseData
{
    public function __construct(
        public int $meta_id,
        public string $key,
        public mixed $value
    ) {
    }
}
