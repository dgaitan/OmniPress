<?php

namespace App\Models\Subscription;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Subscription\KindhumanSubscription
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $customer_id
 * @property int|null $active_order_id
 * @property string|null $uuid
 * @property string $status
 * @property string|null $customer_email
 * @property string|null $payment_method
 * @property int $total
 * @property string $start_date
 * @property string|null $end_date
 * @property string|null $next_payment_date
 * @property string|null $last_payment
 * @property string|null $billing_address
 * @property string|null $shipping_address
 * @property string|null $shipping_method
 * @property int|null $payment_intents
 * @property string|null $payment_interval
 * @property string|null $cause
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription whereActiveOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription whereBillingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription whereCause($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription whereCustomerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription whereLastPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription whereNextPaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription wherePaymentIntents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription wherePaymentInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription whereShippingMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscription whereUuid($value)
 * @mixin \Eloquent
 */
class KindhumanSubscription extends Model
{
    use HasFactory;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'next_payment_date' => 'date',
        'last_intent' => 'date',
        'shipping_address' => 'object',
        'billing_address' => 'object',
        'cause' => 'object'
    ];

    protected $fillable = [
        'status',
        'customer_id',
        'customer_email',
        'start_date',
        'end_date',
        'next_payment_date',
        'last_intent',
        'active_order_id',
        'total',
        'billing_address',
        'shipping_address',
        'payment_intents',
        'cause',
        'payment_method'
    ];

    /**
     * Orders
     *
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(
            KindhumanSubscription::class, 
            'kindhuman_subscription_id'
        );
    }

    /**
     * Logs
     *
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(
            KindhumanSubscriptionLog::class,
            'subscription_id'
        );
    }

    /**
     * Items
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(
            KindhumanSubscriptionItem::class,
            'subscription_id'
        );
    }
}
