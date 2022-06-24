<?php

namespace App\Data\Order;

use App\Data\BaseData;
use Spatie\LaravelData\DataCollection;

class TaxLineData extends BaseData
{
    public static $id_field = 'tax_line_id';

    protected static $priceFields = [
        'tax_total',
        'shipping_tax_total',
    ];

    protected static $collectionFields = [
        'meta_data' => \App\Data\Shared\MetaData::class,
    ];

    protected static $booleanFields = [
        'compound',
    ];

    public function __construct(
        public int $tax_line_id,
        public string $rate_code,
        public string $rate_id,
        public string $label,
        public bool $compound,
        public float $tax_total,
        public float|null $shipping_tax_total,
        /** @var \App\Data\Shared\MetaData[] */
        public ?DataCollection $meta_data,
    ) {
    }
}
