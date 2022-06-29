<?php

namespace App\Models\Printforia;

use App\Models\WooCommerce\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Printforia\PrintforiaOrderItem
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $order_id
 * @property int $product_id
 * @property string|null $customer_item_reference
 * @property string|null $printforia_sku
 * @property string|null $kindhumans_sku
 * @property int $quantity
 * @property string|null $description
 * @property string|null $prints
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderItem whereCustomerItemReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderItem whereKindhumansSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderItem wherePrintforiaSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderItem wherePrints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderItem whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $printforia_item_id
 * @property-read \App\Models\Printforia\PrintforiaOrder|null $order
 * @property-read Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderItem wherePrintforiaItemId($value)
 */
class PrintforiaOrderItem extends Model
{
    use HasFactory;

    /**
     * Field casts
     *
     * @var array
     */
    protected $casts = [
        'prints' => 'array',
    ];

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'customer_item_reference',
        'printforia_sku',
        'kindhumans_sku',
        'quantity',
        'description',
        'prints',
        'printforia_item_id',
    ];

    /**
     * Printforia Order
     *
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(PrintforiaOrder::class, 'order_id');
    }

    /**
     * Product kindhumans
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
