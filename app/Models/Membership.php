<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WooCommerce\Customer;

/**
 * App\Models\Membership
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $customer_id
 * @property string $customer_email
 * @property string $start_at
 * @property string $end_at
 * @property string $price
 * @property string $shipping_status
 * @property string $status
 * @property int|null $pending_order_id
 * @property string|null $last_payment_intent
 * @property int $payment_intents
 * @property int $kind_cash_id
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership query()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereCustomerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereKindCashId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereLastPaymentIntent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership wherePaymentIntents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership wherePendingOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereShippingStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read Customer|null $customer
 * @property-read \App\Models\KindCash|null $kindCash
 */
class Membership extends Model
{
    use HasFactory;

    protected $casts = [
        'start_at' => 'date',
        'end_at' => 'date',
        'last_payment_intent' => 'date',
        'price' => 'decimal:2'
    ];

    protected $fillable = [
        'customer_id',
        'customer_email', 'start_at',
        'end_at', 'price', 'shipping_status',
        'status', 'pending_order_id', 'last_payment_intent',
        'payment_intents'
    ];

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function kindCash() {
        return $this->hasOne(KindCash::class, 'kind_cash_id');
    }
}
