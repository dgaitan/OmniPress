<?php

namespace App\Models\Subscription;

use App\Models\WooCommerce\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Subscription\SubscriptionProduct
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $expiration_date
 * @property int|null $price
 * @property int|null $fee
 * @property bool $use_parent_settings
 * @property object|null $intervals
 * @property-read Product $product
 *
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct whereExpirationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct whereIntervals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct whereUseParentSettings($value)
 * @mixin \Eloquent
 */
class SubscriptionProduct extends Model
{
    use HasFactory;

    protected $casts = [
        'intervals' => 'object',
        'expiration_date' => 'date',
    ];

    protected $fillable = [
        'expiration_date',
        'price',
        'fee',
        'use_parent_settings',
        'intervals',
    ];

    /**
     * Product
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
