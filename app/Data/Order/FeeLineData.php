<?php

namespace App\Data\Order;

use App\Data\BaseData;
use Spatie\LaravelData\DataCollection;

class FeeLineData extends BaseData {
    public static $id_field = 'fee_line_id';

    protected static $priceFields = [
        'total_tax',
        'total'
    ];

    protected static $collectionFields = [
        'meta_data' => \App\Data\Shared\MetaData::class,
    ];

    public function __construct(
        public int $fee_line_id,
        public string $name,
        public string $tax_class,
        public string $tax_status,
        public float $total,
        public float $total_tax,
        public array $taxes,
        /** @var \App\Data\Shared\MetaData[] */
        public ?DataCollection $meta_data,

    ) {

    }
}