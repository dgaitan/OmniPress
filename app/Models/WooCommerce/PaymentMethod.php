<?php

namespace App\Models\WooCommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\WooCommerce\PaymentMethod
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $payment_method_id
 * @property string $title
 * @property string|null $description
 * @property int $order
 * @property bool $enabled
 * @property string $method_title
 * @property string|null $method_description
 * @property mixed|null $method_supports
 * @property mixed|null $settings
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereMethodDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereMethodSupports($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereMethodTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\Order[] $orders
 * @property-read int|null $orders_count
 */
class PaymentMethod extends Model
{
    use HasFactory;

    protected $casts = [
        'method_supports' => 'array',
        'settings' => 'array',
    ];

    protected $fillable = [
        'payment_method_id',
        'title',
        'description',
        'order',
        'enabled',
        'method_title',
        'method_description',
        'method_supports',
        'settings',
    ];

    /**
     * Orders
     *
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'payment_id');
    }
}
