<?php

namespace App\Data\Order;

use App\Data\BaseData;
use Spatie\LaravelData\DataCollection;

class CouponLineData extends BaseData {
    public static $id_field = 'coupon_line_id';

    protected static $priceFields = [
        'discount',
        'discount_tax'
    ];

    protected static $collectionFields = [
        'meta_data' => \App\Data\Shared\MetaData::class,
    ];

    public function __construct(
        public int $coupon_line_id,
        public string $code,
        public float $discount,
        public float $discount_tax,
        /** @var \App\Data\Shared\MetaData[] */
        public ?DataCollection $meta_data,

    ) {

    }
}