<?php

namespace App\Models\Printforia;

use App\Models\WooCommerce\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Printforia\PrintforiaOrder
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $order_id
 * @property string|null $customer_reference
 * @property string|null $ship_to_address
 * @property string|null $return_to_address
 * @property string|null $shipping_method
 * @property string|null $ioss_number
 * @property string $status
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereCustomerReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereIossNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereReturnToAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereShipToAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereShippingMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PrintforiaOrder extends Model
{
    use HasFactory;

    /**
     * Field Casts
     *
     * @var string[]
     */
    protected $casts = [
        'ship_to_address' => 'array',
        'return_to_address' => 'array'
    ];

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'customer_reference',
        'ship_to_address',
        'return_to_address',
        'shipping_method',
        'ioss_number',
        'status'
    ];

    /**
     * Order Related to this printforia order
     *
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Printforia items
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(PrintforiaOrderItem::class, 'order_id');
    }
}
