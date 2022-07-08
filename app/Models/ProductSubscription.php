<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductSubscription
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $status
 * @property string $uuid
 * @property int $customer_id
 * @property string $customer_email
 * @property string $total
 * @property int|null $payment_method_id
 * @property string $start_date
 * @property string|null $end_date
 * @property string|null $next_payment
 * @property string|null $last_payment
 * @property string|null $last_intent_date
 * @property mixed|null $billing
 * @property mixed|null $shipping
 * @property string $payment_interval
 * @property string|null $cause
 * @property string|null $shipping_method
 * @property int|null $payment_intents
 * @property int|null $active_order_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereActiveOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereBilling($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereCause($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereCustomerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereLastIntentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereLastPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereNextPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription wherePaymentIntents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription wherePaymentInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereShippingMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereUuid($value)
 * @mixin \Eloquent
 */
class ProductSubscription extends Model
{
    use HasFactory;
}
