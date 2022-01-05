<?php

namespace App\Models\WooCommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\ProductSetting;
use App\Casts\MetaData;

/**
 * App\Models\WooCommerce\Product
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $product_id
 * @property int|null $parent_id
 * @property string $name
 * @property string $slug
 * @property string $permalink
 * @property string $sku
 * @property string $date_created
 * @property string|null $date_modified
 * @property string $type
 * @property string $status
 * @property bool $featured
 * @property bool $on_sale
 * @property bool $purchasable
 * @property bool $virtual
 * @property bool $manage_stock
 * @property int $stock_quantity
 * @property string $stock_status
 * @property bool $sold_individually
 * @property mixed $price
 * @property mixed $regular_price
 * @property mixed $sale_price
 * @property array $settings
 * @property array $meta_data
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDateCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDateModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereManageStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMetaData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereOnSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePermalink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePurchasable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereRegularPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSoldIndividually($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStockQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStockStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereVirtual($value)
 * @mixin \Eloquent
 * @property-read Product|null $children
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\ProductImage[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\ProductAttribute[] $attributes
 * @property-read int|null $attributes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\Tag[] $tags
 * @property-read int|null $tags_count
 * @property int|null $service_id
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereServiceId($value)
 */
class Product extends Model
{
    use HasFactory;

    protected $casts = [
        'price' => 'decimal:0',
        'regular_price' => 'decimal:0',
        'sale_price' => 'decimal:0',
        'settings' => ProductSetting::class,
        'meta_data' => MetaData::class
    ];

    protected $fillable = [
        'product_id',
        'parent_id',
        'name',
        'slug',
        'permalink',
        'sku',
        'date_created',
        'date_modified',
        'type',
        'status',
        'featured',
        'on_sale',
        'purchasable',
        'virtual',
        'manage_stock',
        'stock_quantity',
        'stock_status',
        'sold_individually',
        'price',
        'regular_price',
        'sale_price',
        'settings',
        'meta_data'
    ];

    /**
     * Child Products
     */
    public function children() {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function images() {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function categories() {
        return $this->belongsToMany(
            Category::class, 
            'product_category', 
            'product_id', 
            'category_id'
        );
    }

    public function tags() {
        return $this->belongsToMany(
            Tag::class,
            'product_tag',
            'product_id',
            'tag_id'
        );
    }

    public function attributes() {
        return $this->hasMany(ProductAttribute::class, 'product_id');
    }
}
