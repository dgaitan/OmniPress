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
        public int|null $variant_id,
        public int $quantity,
        public string|null $tax_class,
        public float|null $subtotal,
        public float|null $subtotal_tax,
        public float|null $total,
        public array $taxes,
        /** @var \App\Data\Shared\MetaData[] */
        public ?DataCollection $meta_data,
        public string|null $sku,
        public float|null $product_price
    ) {

    }
}
