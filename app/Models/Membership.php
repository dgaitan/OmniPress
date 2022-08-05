<?php

namespace App\Models;

use App\Mail\Memberships\MembershipCancelled;
use App\Mail\Memberships\MembershipExpired;
use App\Mail\Memberships\MembershipRenewed;
use App\Mail\Memberships\PaymentNotFound;
use App\Mail\Memberships\RenewalReminder;
use App\Mail\Memberships\RenewError;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

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
 *
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
 *
 * @property-read Customer|null $customer
 * @property-read \App\Models\KindCash|null $kindCash
 * @property bool|null $user_picked_gift
 * @property int|null $gift_product_id
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $giftProducts
 * @property-read int|null $gift_products_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereGiftProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereUserPickedGift($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MembershipLog[] $logs
 * @property-read int|null $logs_count
 * @property int|null $product_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereProductId($value)
 *
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
        'payment_intents', 'user_picked_gift', 'gift_product_id',
    ];

    /**
     * [customer description]
     *
     * @return [type] [description]
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * [kindCash description]
     *
     * @return [type] [description]
     */
    public function kindCash()
    {
        return $this->hasOne(KindCash::class);
    }

    /**
     * [logs description]
     *
     * @return [type] [description]
     */
    public function logs()
    {
        return $this->hasMany(MembershipLog::class, 'membership_id');
    }

    /**
     * Membership Product Related to this Membership.
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get Order related to this membership
     *
     * @return Collection
     */
    public function orders(): Builder
    {
        return \App\Models\WooCommerce\Order::where('membership_id', $this->id);
    }

    /**
     * [giftProducts description]
     *
     * @return [type] [description]
     */
    public function giftProducts()
    {
        return $this->belongsToMany(
            Product::class,
            'membership_product',
            'membership_id',
            'product_id'
        )->as('products')->withTimestamps();
    }

    /**
     * Current Order
     *
     * @return Order
     */
    public function getCurrentOrder(): Order
    {
        return $this->orders()
            ->orderBy('date_created', 'desc')
            ->first();
    }

    /**
     * [toArray description]
     *
     * @param  bool  $isSingle [description]
     * @return array            [description]
     */
    public function toArray($isSingle = false): array
    {
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
     *
     * @return bool [description]
     */
    public function isInRenewal(): bool
    {
        return $this->status === self::IN_RENEWAL_STATUS;
    }

    /**
     * [is_active description]
     *
     * @return bool [description]
     */
    public function isActive(): bool
    {
        return $this->status === self::ACTIVE_STATUS;
    }

    /**
     * [isAwaitingPickGift description]
     *
     * @return bool [description]
     */
    public function isAwaitingPickGift(): bool
    {
        return $this->status === self::AWAITING_PICK_GIFT_STATUS;
    }

    /**
     * [isCancelled description]
     *
     * @return bool [description]
     */
    public function isCancelled(): bool
    {
        return $this->status === self::CANCELLED_STATUS;
    }

    /**
     * [isExpired description]
     *
     * @return bool [description]
     */
    public function isExpired(): bool
    {
        return $this->status === self::EXPIRED_STATUS;
    }

    /**
     * Check if the membership expires in 3, 5, or 15 days.
     *
     * First compare the days to know if we're on the range.
     * Then Send renewal reminder if we're on the range.
     *
     * @param  int  $time
     * @return bool
     */
    public function maybeSendRenewalReminder(int $time = 1): bool
    {
        if (! $this->isActive()) {
            return false;
        }
        $possibleDays = [3, 5, 15];

        if (in_array($this->daysUntilRenewal(), $possibleDays)) {
            $this->sendRenewalReminder($time, $this->daysUntilRenewal());
            $this->logs()->create([
                'description' => sprintf(
                    '%s days email reminder was sent to customer.',
                    $this->daysUntilRenewal()
                ),
            ]);

            return true;
        }

        return false;
    }

    /**
     * Maybe Renew membership if it is expired
     *
     * @param  bool  $force
     * @param  int  $time
     * @return void
     */
    public function maybeRenewIfExpired(
        bool $force = false,
        int $time = 0
    ): bool {
        if (! $this->isInRenewal()) {
            return false;
        }

        $possibleDays = [15, 5, 3];

        if (in_array($this->daysExpired(), $possibleDays)) {
            $this->maybeRenew(force: $force, index: $time);

            return true;
        }

        return false;
    }

    /**
     * Send notification to customer that membership
     * has been renewed but the customer still not
     * choose a gift product
     *
     * @param  int  $time
     * @return bool
     */
    public function maybeRememberThatMembershipHasRenewed(
        int $time = 0
    ): bool {
        if (! $this->isAwaitingPickGift()) {
            return false;
        }
        $possibleDays = [1, 2, 5, 20, 30];

        if (in_array($this->daysAfterRenewal(), $possibleDays)) {
            $this->sendMembershipRenewedMail($time);
            $this->logs()->create([
                'description' => 'An email to reminder customer to pick the gift product includes on membership was sent.',
            ]);

            return true;
        }

        return false;
    }

    /**
     * Is this membership expiring today?
     *
     * @return bool
     */
    public function expireToday(): bool
    {
        return $this->daysUntilRenewal() === 0;
    }

    /**
     * Parse the today date to match without time, only date
     *
     * @return \Carbon\Carbon
     */
    protected function today(): \Carbon\Carbon
    {
        return \Carbon\Carbon::parse(
            \Carbon\Carbon::now()->toDateString()
        );
    }

    /**
     * Days until renewal
     *
     * @todo Better find a strategy to compare by hours.
     *
     * @return [type] [description]
     */
    public function daysUntilRenewal(): int
    {
        return max($this->today()->diffInDays($this->end_at, false), 0);
    }

    /**
     * [daysExpired description]
     *
     * @return [type] [description]
     */
    public function daysExpired(): int
    {
        return max($this->end_at->diffInDays($this->today(), false), 0);
    }

    /**
     * [daysAfterRenewal description]
     *
     * @return [type] [description]
     */
    public function daysAfterRenewal(): int
    {
        return max($this->last_payment_intent->diffInDays($this->today(), false), 0);
    }

    /**
     * Expire a membership
     *
     * @param  string  $reason
     * @return self
     */
    public function expire(string $reason = '')
    {
        $this->status = self::EXPIRED_STATUS;
        $this->shipping_status = self::SHIPPING_CANCELLED_STATUS;
        $this->save();

        if ($reason) {
            $this->logs()->create(['description' => $reason]);
        }

        $this->sendMembershipExpiredMail();

        return $this;
    }

    public function cancell(string $reason = '')
    {
        $this->status = self::CANCELLED_STATUS;
        $this->save();

        if ($reason) {
            $this->logs()->create(['description' => $reason]);
        }

        $this->sendCancelledEmail();
    }

    /**
     * Send Membership Cancelled Email
     *
     * @param  int  $time
     * @return voiud
     */
    public function sendCancelledEmail(int $time = 1): void
    {
        Mail::to($this->customer->email)
            ->later(
                now()->addSeconds($time),
                new MembershipCancelled($this)
            );
    }

    /**
     * Send renewal reminder emails or what ever.
     *
     * @return void
     */
    public function sendRenewalReminder(int $time = 1, int $days = 0): void
    {
        Mail::to($this->customer->email)
            ->later(
                now()->addSeconds($time),
                new RenewalReminder($this, $days)
            );
    }

    /**
     * [sendPaymentNotFoundNotification description]
     *
     * @return [type] [description]
     */
    public function sendPaymentNotFoundNotification(int $time = 1): void
    {
        Mail::to($this->customer->email)
            ->later(now()->addSeconds($time), new PaymentNotFound($this));
    }

    /**
     * Send membership renewed mail
     *
     * @return void
     */
    public function sendMembershipRenewedMail(int $time = 1): void
    {
        Mail::to($this->customer->email)
            ->later(now()->addSeconds($time), new MembershipRenewed($this));
    }

    /**
     * Send Membership Expired Mail
     *
     * @param  int  $time
     * @return void
     */
    public function sendMembershipExpiredMail(int $time = 1): void
    {
        Mail::to($this->customer->email)
            ->later(now()->addSeconds($time), new MembershipExpired($this));
    }

    /**
     * Send Renewal Error Mail
     *
     * @param  int  $time
     * @return void
     */
    public function sendRenewalErrorMail(
        int $time = 1,
        string $message = ''
    ): void {
        Mail::to($this->customer->email)
            ->later(now()->addSeconds($time), new RenewError($this, $message));
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
     * @param  bool  $force - Normally the trigger will validate if the membership is expired unless we force it.
     * @param  int  $gift_product_id - Attach a Gift Product Id
     * @param  string  $stripe_token - Is possible the user does not store the card, so is necessary pass the stripe_token to make one time payment.
     * @return Membership
     *
     * @throws if Membership isn't expired unless we force it.
     * @throws if Membership has a status different that active or in-renewal.
     * @throws if ocurred an error during auto payment.
     * @throws if customer doesn't have a payment method.
     */
    public function maybeRenew($force = false, int $index = 1)
    {
        \App\Jobs\Memberships\RenewMembershipJob::dispatch($this->id, $force, $index);
    }

    /**
     * catch error code when something fails
     * if we try to renew it
     *
     * @param  string  $message
     * @return Membership
     */
    public function catchRenewalError(string $message = ''): Membership
    {
        $this->last_payment_intent = \Carbon\Carbon::now();
        $this->payment_intents = $this->payment_intents + 1;

        if ($this->daysExpired() > 30) {
            $this->status = Membership::EXPIRED_STATUS;
            $this->expire('Membership expired because was impossible find a payment method in 30 days');
        } else {
            $this->sendRenewalErrorMail(1, $message);
        }

        $this->logs()->create([
            'description' => sprintf(
                'Membership Renewal Failed with error: %s',
                $message
            ),
        ]);

        $this->save();

        return $this;
    }

    /**
     * [getStatuses description]
     *
     * @return [type] [description]
     */
    public static function getStatuses(): array
    {
        $statuses = [];

        $status[] = [
            'slug' => self::ACTIVE_STATUS,
            'label' => 'Active',
        ];
        $status[] = [
            'slug' => self::IN_RENEWAL_STATUS,
            'label' => 'In Renewal',
        ];
        $status[] = [
            'slug' => self::AWAITING_PICK_GIFT_STATUS,
            'label' => 'Awaiting Pick Gift',
        ];
        $status[] = [
            'slug' => self::CANCELLED_STATUS,
            'label' => 'Cancelled',
        ];
        $status[] = [
            'slug' => self::EXPIRED_STATUS,
            'label' => 'Expired',
        ];

        return $status;
    }

    /**
     * Get the Membership Shipping Status availables
     *
     * @return array
     */
    public static function getShippingStatuses(): array
    {
        $statuses = [
            [
                'slug' => self::SHIPPING_SHIPPED_STATUS,
                'label' => 'Shipped',
            ],
            [
                'slug' => self::SHIPPING_NO_SHIP_STATUS,
                'label' => 'No Ship',
            ],
            [
                'slug' => self::SHIPPING_PENDING_STATUS,
                'label' => 'Pending',
            ],
            [
                'slug' => self::SHIPPING_CANCELLED_STATUS,
                'label' => 'Cancelled',
            ],
            [
                'slug' => 'N/A',
                'label' => 'N/A',
            ],
        ];

        return $statuses;
    }

    /**
     * Is the status a valid membership shipping status?
     *
     * @param  string  $status
     * @return bool
     */
    public static function isValidShippingStatus(string $status): bool
    {
        return in_array($status, [
            self::SHIPPING_CANCELLED_STATUS,
            self::SHIPPING_NO_SHIP_STATUS,
            self::SHIPPING_SHIPPED_STATUS,
            self::SHIPPING_PENDING_STATUS,
        ]);
    }

    /**
     * Get a message to log
     *
     * @param  string  $code [description]
     * @return string
     */
    public static function logMessages(string $code): string
    {
        $messages = [
            'created_by_checkout' => 'Membership created via kindhumans checkout',
        ];

        return array_key_exists($code, $messages) ? $messages[$code] : '';
    }

    /**
     * Handle Some actions on model boot
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Forget Membership Cache
        static::saving(function () {
            Cache::tags('memberships')->flush();
        });
    }
}
