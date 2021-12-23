<?php

namespace App\Data\Http;

use App\Casts\ProductSetting;
use Spatie\LaravelData\DataCollection;
use App\Data\BaseData;
use App\Data\Product\ProductSettingData;

class ProductData extends BaseData {
    public static $id_field = 'product_id';

    protected static $priceFields = [
        'price',
        'regular_price',
        'sale_price'
    ];

    protected static $collectionFields = [
        'meta_data' => \App\Data\Shared\MetaData::class,
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
        public ?string $sku,
        public ?float $price = 0.0,
        public ?float $regular_price = 0.0,
        public ?float $sale_price = 0.0,
        public bool $on_sale,
        public bool $purchasable,
        public ?bool $virtual = false,
        public ?bool $downloadable = false,
        public bool $manage_stock,
        public ?int $stock_quantity = 0,
        public string $stock_status,
        public bool $sold_individually,
        public ?int $parent_id = 0,
        /** @var \App\Data\Product\CategoryData[] */
        public ?DataCollection $categories,
        /** @var \App\Data\Product\TagData[] */
        public ?DataCollection $tags,
        /** @var \App\Data\Product\ImageData[] */
        public ?DataCollection $images,
        /** @var \App\Data\Product\AttributeData[] */
        public ?DataCollection $attributes,
        /** @var \App\Data\Shared\MetaData[] */
        public ?DataCollection $meta_data,
        public ProductSettingData $settings,
    ) {
        $this->stock_quantity = $stock_quantity ?? 0;
    }

    public static function _processResponse(array $data): array {
        $_data = parent::_processResponse($data);
        $_data['settings'] = ProductSettingData::_fromResponse($data);
        
        return $_data;
        
    }
}