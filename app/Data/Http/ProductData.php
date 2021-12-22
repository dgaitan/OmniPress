<?php

namespace App\Data\Http;

use Spatie\LaravelData\DataCollection;
use App\Data\BaseData;
use App\Data\Product\DimensionsData;

class ProductData extends BaseData {
    public static $id_field = 'product_id';

    protected static $priceFields = [
        'price',
        'regular_price',
        'sale_price'
    ];

    protected static $floatFields = [
        'weight'
    ];

    protected static $collectionFields = [
        'meta_data' => \App\Data\Shared\MetaData::class,
        'downloads' => \App\Data\Product\DownloadData::class,
        'categories' => \App\Data\Product\CategoryData::class,
        'tags' => \App\Data\Product\TagData::class,
        'attributes' => \App\Data\Product\AttributeData::class,
        'images' => \App\Data\Product\ImageData::class
    ];
    
    public function __construct(
        public int $product_id,
        public string $name,
        public string $slug,
        public ?string $permalink = '',
        public ?string $date_created,
        public ?string $date_modified,
        public string $type,
        public string $status,
        public bool $featured,
        public string $catalog_visibility,
        public ?string $description,
        public ?string $short_description,
        public ?string $sku,
        public ?float $price = 0.0,
        public ?float $regular_price = 0.0,
        public ?float $sale_price = 0.0,
        public ?string $date_on_sale_from,
        public ?string $date_on_sale_to,
        public ?string $price_html,
        public bool $on_sale,
        public bool $purchasable,
        public ?int $total_sales = 0,
        public ?bool $virtual = false,
        public ?bool $downloadable = false,
        /** @var \App\Data\Product\DownloadData[] */
        public ?DataCollection $downloads,
        public ?int $download_limit = 0,
        public ?int $download_expiry = 0,
        public ?string $external_url = '',
        public ?string $button_text,
        public ?string $tax_status = 'taxable',
        public ?string $tax_class,
        public bool $manage_stock,
        public ?int $stock_quantity = 0,
        public string $stock_status,
        public ?string $backorders,
        public bool $backorders_allowed,
        public bool $backordered,
        public bool $sold_individually,
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
        public ?int $parent_id = 0,
        public ?string $purchase_note,
        /** @var \App\Data\Product\CategoryData[] */
        public ?DataCollection $categories,
        /** @var \App\Data\Product\TagData[] */
        public ?DataCollection $tags,
        /** @var \App\Data\Product\ImageData[] */
        public ?DataCollection $images,
        /** @var \App\Data\Product\AttributeData[] */
        public ?DataCollection $attributes,
        public ?array $default_attributes = [],
        public ?array $variations = [],
        public ?array $grouped_products = [],
        public ?int $menu_order,
        /** @var \App\Data\Shared\MetaData[] */
        public ?DataCollection $meta_data,
    ) {

    }
}