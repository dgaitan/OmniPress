<?php

namespace App\Models\WooCommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WooCommerce\ProductAttribute
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $attribute_id
 * @property string|null $name
 * @property int|null $position
 * @property bool|null $visible
 * @property bool|null $variation
 * @property mixed|null $options
 * @property int|null $product_id
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereVariation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereVisible($value)
 * @mixin \Eloquent
 */
class ProductAttribute extends Model
{
    use HasFactory;

    protected $casts = [
        'options' => 'array'
    ];

    protected $fillable = [
        'attribute_id',
        'name',
        'position',
        'visible',
        'variation',
        'options',
        'product_id'
    ];

    public function product() {
        return $this->belongTo(Product::class, 'product_id');
    }

    // public function getOptionsAttribute(string $value) {
    //     return json_decode($value, true);
    // }

    // public function setOptionsAttribute($value) {
    //     dd($value);
    //     $this->attributes['options'] = json_encode($value);
    // }
}
