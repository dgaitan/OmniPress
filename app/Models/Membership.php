<?php

namespace App\Models;

use App\Mail\Memberships\MembershipRenewed;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\Memberships\RenewalReminder;
use App\Mail\Memberships\PaymentNotFound;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Product;
use App\Tasks\WooCommerceTask;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Membership
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $customer_id
 * @property string $customer_email
 * @property \Illuminate\Support\Carbon $start_at
 * @property \Illuminate\Support\Carbon $end_at
 * @property string $price
 * @property string $shipping_status
 * @property string $status
 * @property int|null $pending_order_id
 * @property \Illuminate\Support\Carbon|null $last_payment_intent
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
 * @property int|null $product_id
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereProductId($value)
 * @property-read Product|null $product
 */
class Membership extends Model
{
    use HasFactory;

    const ACTIVE_STATUS = 'active';
    const CANCELLED_STATUS = 'cancelled';
    const EXPIRED_STATUS = 'expired';
    const IN_RENEWAL_STATUS = 'in_renewal';
    const AWAITING_PICK_GIFT_STATUS = 'awaiting_pick_gift';

    // Shipping Statuses
    const SHIPPING_PENDING_STATUS = 'pending';
    const SHIPPING_CANCELLED_STATUS = 'cancelled';
    const SHIPPING_SHIPPED_STATUS = 'shipped';
    const SHIPPING_NO_SHIP_STATUS = 'no_ship';

    protected $casts = [
        'start_at' => 'date',
        'end_at' => 'date',
        'last_payment_intent' => 'date',
    ];

    protected $fillable = [
        'customer_id',
        'customer_email', 'start_at',
        'end_at', 'price', 'shipping_status', 'product_id',
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
     * Membership Product Related to this Membership.
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get Order related to this membership
     *
     * @return Collection
     */
    public function orders(): Collection {
        return \App\Models\WooCommerce\Order::where('membership_id', $this->id);
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

        $data['customer'] = $this->customer->toArray();
        $data['cash'] = $this->kindCash->toArray($isSingle);
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
     * [daysUntilRenewal description]
     * @return [type] [description]
     */
    public function daysUntilRenewal(): int {
        return max(\Carbon\Carbon::now()->diffInDays($this->end_at, false), 0);
    }

    /**
     * [daysExpired description]
     * @return [type] [description]
     */
    public function daysExpired(): int {
        return max($this->end_at->diffInDays(\Carbon\Carbon::now(), false), 0);
    }

    /**
     * [daysAfterRenewal description]
     * @return [type] [description]
     */
    public function daysAfterRenewal(): int {
        return max($this->last_payment_intent->diffInDays(\Carbon\Carbon::now(), false), 0);
    }

    /**
     * Expire a membership
     *
     * @param string $reason
     * @return self
     */
    public function expire(string $reason = '') {
        $this->status = self::EXPIRED_STATUS;
        $this->shipping_status = self::SHIPPING_CANCELLED_STATUS;
        $this->save();

        if ($reason) {
            $this->logs()->create(['description' => $reason]);
        }

        return $this;
    }

    /**
     * Send renewal reminder emails or what ever.
     *
     * @return void
     */
    public function sendRenewalReminder(int $time = 1): void {
        Mail::to($this->customer->email)
            ->later(now()->addMinutes($time), new RenewalReminder($this));
    }

    /**
     * [sendPaymentNotFoundNotification description]
     * @return [type] [description]
     */
    public function sendPaymentNotFoundNotification(int $time = 1): void {
        Mail::to($this->customer->email)
            ->later(now()->addMinutes($time), new PaymentNotFound($this));
    }

    /**
     * Send membership renewed mail
     *
     * @return void
     */
    public function sendMembershipRenewedMail(int $time = 1): void {
        Mail::to($this->customer->email)
            ->later(now()->addMinutes($time), new MembershipRenewed($this));
    }

    /**
     * Maybe Renew a Membership.
     *
     * Normally, the customers should has a card stored to can make
     * the auto-payment. The Membership should be in active or in-renewal status.
     *
     * Since 2021, kindhumans gives a gift when the users buy or renewal their
     * membership. So we have a flow to know what the customer picks. This
     * auto-renew trigger create the new order and if all happens with success
     * the membership will change to awaiting-pick-gift status. This will change
     * until user select a product.
     *
     * @param boolean $force - Normally the trigger will validate if the membership is expired unless we force it.
     * @param integer $gift_product_id - Attach a Gift Product Id
     * @param string $stripe_token - Is possible the user does not store the card, so is necessary pass the stripe_token to make one time payment.
     * @throws if Membership isn't expired unless we force it.
     * @throws if Membership has a status different that active or in-renewal.
     * @throws if ocurred an error during auto payment.
     * @throws if customer doesn't have a payment method.
     * @return Membership
     */
    public function maybeRenew($force = false) {
        \App\Jobs\Memberships\RenewMembershipJob::dispatch($this->id, $force);
    }

    /**
     * [getStatuses description]
     * @return [type] [description]
     */
    public static function getStatuses(): array {
        $statuses = [];

        $status[] = [
            'slug' => self::ACTIVE_STATUS,
            'label' => 'Active'
        ];
        $status[] = [
            'slug' => self::IN_RENEWAL_STATUS,
            'label' => 'In Renewal'
        ];
        $status[] = [
            'slug' => self::AWAITING_PICK_GIFT_STATUS,
            'label' => 'Awaiting Pick Gift'
        ];
        $status[] = [
            'slug' => self::CANCELLED_STATUS,
            'label' => 'Cancelled'
        ];
        $status[] = [
            'slug' => self::EXPIRED_STATUS,
            'label' => 'Expired'
        ];

        return $status;
    }

    /**
     * Get the Membership Shipping Status availables
     *
     * @return array
     */
    public static function getShippingStatuses(): array {
        $statuses = [
            [
                'slug' => self::SHIPPING_SHIPPED_STATUS,
                'label' => 'Shipped'
            ],
            [
                'slug' => self::SHIPPING_NO_SHIP_STATUS,
                'label' => 'No Ship'
            ],
            [
                'slug' => self::SHIPPING_PENDING_STATUS,
                'label' => 'Pending'
            ],
            [
                'slug' => self::SHIPPING_CANCELLED_STATUS,
                'label' => 'Cancelled'
            ]
        ];
        return $statuses;
    }

    /**
     * Is the status a valid membership shipping status?
     *
     * @param string $status
     * @return boolean
     */
    public static function isValidShippingStatus(string $status): bool {
        return in_array($status, [
            self::SHIPPING_CANCELLED_STATUS,
            self::SHIPPING_NO_SHIP_STATUS,
            self::SHIPPING_SHIPPED_STATUS,
            self::SHIPPING_PENDING_STATUS
        ]);
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
