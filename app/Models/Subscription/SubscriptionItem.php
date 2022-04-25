<?php

namespace App\Models\Subscription;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Subscription\SubscriptionItem
 *
 * @property int $id
 * @property int $subscription_id
 * @property string $stripe_id
 * @property string $stripe_product
 * @property string $stripe_price
 * @property int|null $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereStripePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereStripeProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SubscriptionItem extends Model
{
    use HasFactory;
}
