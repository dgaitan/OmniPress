<?php

namespace App\Models\Subscription;

use App\Models\WooCommerce\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Subscription\KindhumanSubscriptionItem
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $subscription_id
 * @property int $product_id
 * @property int|null $regular_price
 * @property int $quantity
 * @property int $price
 * @property int $fee
 * @property int|null $total
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionItem whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionItem whereRegularPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionItem whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionItem whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class KindhumanSubscriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'subscription_id',
        'regular_price',
        'price',
        'fee',
        'total',
        'quantity'
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(KindhumanSubscription::class, 'subscription_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
