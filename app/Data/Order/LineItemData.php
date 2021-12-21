<?php

namespace App\Data\Order;

use App\Data\BaseData;
use Spatie\LaravelData\DataCollection;

class LineItemData extends BaseData {

    public static $id_field = 'line_item_id';

    protected static $priceFields = [
        'subtotal',
        'subtotal_tax',
        'total',
        'product_price'
    ];

    protected static $collectionFields = [
        'meta_data' => \App\Data\Shared\MetaData::class,
    ];

    public function __construct(
        public int $line_item_id,
        public string $name,
        public int $product_id,
        public ?int $variant_id = 0,
        public int $quantity = 1,
        public ?string $tax_class = '',
        public ?float $subtotal = 0,
        public ?float $subtotal_tax = 0,
        public ?float $total = 0,
        public array $taxes,
        /** @var \App\Data\Shared\MetaData[] */
        public ?DataCollection $meta_data,
        public string $sku,
        public ?float $product_price = 0
    ) {

    }
}