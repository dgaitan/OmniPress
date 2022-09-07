<?php

namespace App\Models\WooCommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WooCommerce\ProductImage
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $product_image_id
 * @property string|null $date_created
 * @property string|null $date_modified
 * @property string|null $src
 * @property string|null $name
 * @property string|null $alt
 * @property int $product_id
 * @property-read \App\Models\WooCommerce\Product $product
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereDateCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereDateModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereProductImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereSrc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductImage extends Model
{
    use HasFactory;

    protected $casts = [
        'date_created' => 'datetime',
        'date_modified' => 'datetime',
    ];

    protected $fillable = [
        'product_id',
        'src',
        'name',
        'alt',
        'date_created',
        'date_modified',
        'product_image_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get Image Url
     *
     * @return string
     */
    public function getImageUrl(): string
    {
        $imageUrl = '';

        if ($this->src) {
            $imageUrl = explode('wp-content/', $this->src);
            $imageUrl = sprintf(
                '%s/wp-content/%s',
                env('ASSET_DOMAIN', 'https://kindhumans.com'),
                end($imageUrl)
            );
        }

        return $imageUrl;
    }

    public function toArray()
    {
        $data = parent::toArray();

        if ($data['src']) {
            $src = explode('wp-content/', $data['src']);
            $data['src'] = sprintf(
                '%s/wp-content/%s',
                env('ASSET_DOMAIN', 'https://kindhumans.com'),
                end($src)
            );
        }

        return $data;
    }
}
