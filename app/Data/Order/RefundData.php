<?php

namespace App\Data\Order;

use App\Data\BaseData;

class RefundData extends BaseData {
    public static $id_field = 'refund_id';

    protected static $priceFields = [
        'total'
    ];

    protected static $collectionFields = [
        'meta_data' => \App\Data\Shared\MetaData::class,
    ];

    public function __construct(
        public int $refund_id,
        public ?string $reason = '',
        public ?float $total = 0
    ) {

    }
}