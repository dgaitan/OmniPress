<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Product;
use App\Casts\Money;

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
 * @property bool|null $user_picked_gift
 * @property int|null $gift_product_id
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $giftProducts
 * @property-read int|null $gift_products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereGiftProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereUserPickedGift($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MembershipLog[] $logs
 * @property-read int|null $logs_count
 */
class Membership extends Model
{
    use HasFactory;

    const ACTIVE_STATUS = 'active';
    const CANCELLED_STATUS = 'cancelled';
    const EXPIRED_STATUS = 'expired';
    const IN_RENEWAL_STATUS = 'in_renewal';
    const AWAITING_PICK_GIFT_STATUS = 'awaiting_pick_gift';

    protected $casts = [
        'start_at' => 'date',
        'end_at' => 'date',
        'last_payment_intent' => 'date',
    ];

    protected $fillable = [
        'customer_id',
        'customer_email', 'start_at',
        'end_at', 'price', 'shipping_status',
        'status', 'pending_order_id', 'last_payment_intent',
        'payment_intents', 'user_picked_gift', 'gift_product_id'
    ];

    /**
     * [customer description]
     * @return [type] [description]
     */
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * [kindCash description]
     * @return [type] [description]
     */
    public function kindCash() {
        return $this->hasOne(KindCash::class);
    }

    /**
     * [logs description]
     * @return [type] [description]
     */
    public function logs() {
        return $this->hasMany(MembershipLog::class, 'membership_id');
    }

    /**
     * [giftProducts description]
     * @return [type] [description]
     */
    public function giftProducts() {
        return $this->belongsToMany(
            Product::class, 
            'membership_product', 
            'membership_id', 
            'product_id'
        )->as('products')->withTimestamps();
    }

    /**
     * [toArray description]
     * @param  boolean $isSingle [description]
     * @return array            [description]
     */
    public function toArray($isSingle = false) : array {
        $data = parent::toArray();

        if ($isSingle) {
            $data['customer'] = $this->customer;
            $data['cash'] = $this->kindCash->toArray(true);
        }

        $data['is_active'] = $this->isActive();
        $data['is_in_renewal'] = $this->isInRenewal();
        $data['is_awaiting_pick_gift'] = $this->isAwaitingPickGift();
        $data['is_expired'] = $this->isExpired();
        $data['is_cancelled'] = $this->isCancelled();

        return $data;
    }

    /**
     * [is_in_renewal description]
     * @return boolean [description]
     */
    public function isInRenewal() : bool {
        return $this->status === self::IN_RENEWAL_STATUS;
    }

    /**
     * [is_active description]
     * @return boolean [description]
     */
    public function isActive() : bool {
        return $this->status === self::ACTIVE_STATUS;
    }

    /**
     * [isAwaitingPickGift description]
     * @return boolean [description]
     */
    public function isAwaitingPickGift() : bool {
        return $this->status === self::AWAITING_PICK_GIFT_STATUS;
    }

    /**
     * [isCancelled description]
     * @return boolean [description]
     */
    public function isCancelled() : bool {
        return $this->status === self::CANCELLED_STATUS;
    }

    /**
     * [isExpired description]
     * @return boolean [description]
     */
    public function isExpired() : bool {
        return $this->status === self::EXPIRED_STATUS;
    }

    /**
     * Get a message to log
     * @param  string $code [description]
     * @return string
     */
    public static function logMessages(string $code): string {
        $messages = [
            'created_by_checkout' => 'Membership created via kindhumans checkout'
        ];

        return array_key_exists($code, $messages) ? $messages[$code] : '';
    }
}
