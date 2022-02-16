<?php

namespace App\Data\Order;

use App\Data\BaseData;
use Spatie\LaravelData\DataCollection;

class ShippingLineData extends BaseData {
    public static $id_field = 'shipping_line_id';

    protected static $priceFields = [
        'total_tax',
        'total'
    ];

    protected static $collectionFields = [
        'meta_data' => \App\Data\Shared\MetaData::class,
    ];

    public function __construct(
        public int $shipping_line_id,
        public string $method_title,
        public string $method_id,
        public float $total,
        public float|null $total_tax,
        public array $taxes,
        /** @var \App\Data\Shared\MetaData[] */
        public ?DataCollection $meta_data,
    ) {

    }
}
