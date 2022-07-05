<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SubscriptionItem
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $product_subscription_id
 * @property int|null $variation_id
 * @property int|null $product_id
 * @property string $price
 * @property string|null $product_admin_slug
 * @property string|null $image
 * @property string|null $expiration_date
 * @property int $quantity
 * @property string $fee
 * @property string $total
 * @property mixed|null $interval_choices
 *
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereExpirationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereIntervalChoices($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereProductAdminSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereProductSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereVariationId($value)
 * @mixin \Eloquent
 */
class ProductSubscriptionItem extends Model
{
    use HasFactory;
}
