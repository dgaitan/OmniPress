<?php

namespace App\Models\WooCommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WooCommerce\OrderLine
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $order_line_id
 * @property string|null $name
 * @property int|null $product_id
 * @property int|null $variation_id
 * @property int $quantity
 * @property string|null $tax_class
 * @property mixed|null $subtotal
 * @property mixed|null $subtotal_tax
 * @property mixed|null $total
 * @property array|null $taxes
 * @property mixed|null $meta_data
 * @property string|null $sku
 * @property mixed|null $price
 * @property-read \App\Models\WooCommerce\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereMetaData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereOrderLineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereSubtotalTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereTaxClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereTaxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereVariationId($value)
 * @mixin \Eloquent
 * @property int|null $order_id
 * @property-read \App\Models\WooCommerce\Order|null $order
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereOrderId($value)
 */
class OrderLine extends Model
{
    use HasFactory;

    protected $casts = [
        'subtotal' => 'decimal:0',
        'subtotal_tax' => 'decimal:0',
        'total' => 'decimal:0',
        'taxes' => 'array',
        'price' => 'decimal:0'
    ];

    protected $fillable = [
        'order_line_id',
        'name',
        'product_id',
        'variation_id',
        'quantity',
        'tax_class',
        'subtotal',
        'subtotal_tax',
        'total',
        'taxes',
        'meta_data',
        'sku',
        'price'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function setNameAttribute($value) {
        $value = explode('-', $value);

        $this->attributes['name'] = implode(' ', $value);
    }
}
