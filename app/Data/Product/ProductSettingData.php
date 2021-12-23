<?php

namespace App\Data\Product;

use Spatie\LaravelData\DataCollection;
use App\Data\BaseData;
use App\Data\Product\DimensionsData;

class ProductSettingData extends BaseData {

    protected static $floatFields = [
        'weight'
    ];

    protected static $collectionFields = [
        'downloads' => \App\Data\Product\DownloadData::class,
    ];
    
    public function __construct(
        public ?string $date_on_sale_from,
        public ?string $date_on_sale_to,
        public ?string $price_html,
        public ?string $short_description,
        public string $catalog_visibility,
        public ?string $description,
        public ?int $total_sales = 0,
        public ?int $download_limit = 0,
        public ?int $download_expiry = 0,
        public ?string $external_url = '',
        public ?string $button_text,
        public ?string $tax_status = 'taxable',
        public ?string $tax_class,
        public ?string $backorders,
        public bool $backorders_allowed,
        public bool $backordered,
        public float $weight,
        public ?DimensionsData $dimensionsData,
        public bool $shipping_required,
        public bool $shipping_taxable,
        public int $shipping_class_id,
        public bool $reviews_allowed,
        public string $average_rating,
        public int $rating_count,
        public ?array $related_ids,
        public ?array $upsell_ids,
        public ?array $cross_sell_ids,
        public ?string $purchase_note,
        public ?array $default_attributes = [],
        public ?array $variations = [],
        public ?array $grouped_products = [],
        public ?int $menu_order,
        /** @var \App\Data\Product\DownloadData[] */
        public ?DataCollection $downloads,
    ) {

    }
}