<?php

namespace App\Models\WooCommerce;

use App\Actions\Donations\AssignOrderDonationAction;
use App\Jobs\SingleWooCommerceSync;
use App\Models\Causes\Cause;
use App\Models\Causes\OrderDonation;
use App\Models\Concerns\HasMetaData;
use App\Models\Concerns\HasMoney;
use App\Models\Printforia\PrintforiaOrder;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Scout\Searchable;

/**
 * App\Models\WooCommerce\Order
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $parent_id
 * @property int $number
 * @property string $order_key
 * @property string $created_via
 * @property string $version
 * @property string $status
 * @property string $currency
 * @property \Illuminate\Support\Carbon $date_created
 * @property \Illuminate\Support\Carbon $date_modified
 * @property mixed $discount_total
 * @property mixed $discount_tax
 * @property mixed $shipping_total
 * @property mixed $shipping_tax
 * @property string $cart_tax
 * @property mixed $total
 * @property mixed $total_tax
 * @property bool $prices_include_tax
 * @property string|null $customer_ip_address
 * @property string|null $customer_user_agent
 * @property string|null $transaction_id
 * @property string|null $date_paid
 * @property string|null $date_completed
 * @property string|null $cart_hash
 * @property bool $set_paid
 * @property array|null $meta_data
 * @property \App\Casts\Address $billing
 * @property \App\Casts\Address $shipping
 * @property int $order_id
 * @property int|null $customer_id
 * @property-read \App\Models\WooCommerce\Customer|null $customer
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBilling($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCartHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCartTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedVia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDateCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDateCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDateModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDatePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscountTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscountTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereMetaData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePricesIncludeTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereSetPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotalTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereVersion($value)
 * @mixin \Eloquent
 * @property int|null $service_id
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereServiceId($value)
 * @property mixed|null $tax_lines
 * @property mixed|null $shipping_lines
 * @property mixed|null $coupon_lines
 * @property mixed|null $fee_lines
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\OrderLine[] $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCouponLines($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereFeeLines($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingLines($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTaxLines($value)
 * @property int|null $membership_id
 * @property bool $has_membership
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereHasMembership($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereMembershipId($value)
 * @property int|null $payment_id
 * @property-read \App\Models\WooCommerce\PaymentMethod|null $paymentMethod
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentId($value)
 * @property array|null $giftcards
 * @property string|null $giftcard_total
 * @property int|null $kindhuman_subscription_id
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereGiftcardTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereGiftcards($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereKindhumanSubscriptionId($value)
 * @property-read PrintforiaOrder|null $printforiaOrder
 * @property-read \Illuminate\Database\Eloquent\Collection|OrderDonation[] $donations
 * @property-read int|null $donations_count
 */
class Order extends Model
{
    use HasFactory;
    use Notifiable;
    use HasMetaData;
    use HasMoney;

    /**
     * Order Casting
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'date_created' => 'datetime',
        'date_completed' => 'datetime',
        'date_modified' => 'datetime',
        'shipping_total' => 'decimal:0',
        'discount_total' => 'decimal:0',
        'discount_tax' => 'decimal:0',
        'shipping_tax' => 'decimal:0',
        'giftcard_total' => 'decimal:0',
        'total_tax' => 'decimal:0',
        'billing' => 'object',
        'shipping' => 'object',
        'meta_data' => 'array',
        'tax_lines' => 'array',
        'shipping_lines' => 'array',
        'fee_lines' => 'array',
        'coupon_lines' => 'array',
        'giftcards' => 'array',
        'has_membership' => 'boolean',
    ];

    protected $fillable = [
        'order_id',
        'parent_id',
        'number',
        'order_key',
        'created_via',
        'version',
        'status',
        'currency',
        'date_created',
        'date_modified',
        'discount_total',
        'discount_tax',
        'shipping_total',
        'shipping_tax',
        'cart_tax',
        'total',
        'total_tax',
        'giftcard_total',
        'prices_include_tax',
        'customer_ip_address',
        'customer_user_agent',
        'transaction_id',
        'date_paid',
        'date_completed',
        'cart_hash',
        'set_paid',
        'meta_data',
        'billing',
        'shipping',
        'customer_id',
        'tax_lines',
        'shipping_lines',
        'fee_lines',
        'coupon_lines',
        'has_membership',
        'membership_id',
        'giftcards',
    ];

    /**
     * An order can have a customer, yes
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * An order should belongs to a payment method.
     *
     * @return BelongsTo
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_id');
    }

    /**
     * Order Items
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderLine::class, 'order_id');
    }

    /**
     * Cause
     *
     * @return Cause|null
     */
    public function getCause(): Cause|null
    {
        if (is_null($this->getMetaValue('cause'))) {
            return null;
        }

        return Cause::whereCauseId($this->getMetaValue('cause'))->first();
    }

    /**
     * Order Donations
     *
     * @return HasMany
     */
    public function donations(): HasMany
    {
        return $this->hasMany(OrderDonation::class);
    }

    /**
     * Total Donations AMount
     *
     * @return Money
     */
    public function totalDonated(): Money
    {
        return $this->getAsMoney($this->donations->sum('amount'));
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getDonations()
    {
        $cacheKey = sprintf('order_donations_for_%s', $this->id);

        if (Cache::tags('orders')->has($cacheKey)) {
            return Cache::tags('orders')->get($cacheKey);
        }

        return Cache::tags('orders')->remember($cacheKey, now()->addYear(), function () {
            return $this->donations->map(function ($donation) {
                return [
                    'id' => $donation->id,
                    'cause' => [
                        'id' => $donation->cause->id,
                        'name' => $donation->cause->name,
                        'type' => $donation->cause->getCauseType(),
                    ],
                    'amount' => [
                        'value' => $donation->amount,
                        'format' => $donation->getMoneyValue('amount')->format(),
                    ],
                ];
            });
        });
    }

    /**
     * Probably an order has one printforia order
     *
     * @return HasOne
     */
    public function printforiaOrder(): HasOne
    {
        return $this->hasOne(PrintforiaOrder::class, 'order_id');
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getCustomerInfo(): string
    {
        return sprintf(
            '%s %s',
            $this->billing->first_name,
            $this->billing->last_name
        );
    }

    public function getTotal(): string
    {
        return sprintf(
            '$ %s',
            number_format((float) $this->total / 100, 2)
        );
    }

    /**
     * Return the shipping address formatted
     *
     * @return string
     */
    public function shippingAddress(): string
    {
        $shipping = '';

        if ($this->shipping) {
            $shipping = sprintf(
                '<p>%s %s<br>%s<br>%s %s %s</p>',
                $this->shipping->first_name,
                $this->shipping->last_name,
                $this->shipping->address_1,
                $this->shipping->city,
                $this->shipping->state,
                $this->shipping->postcode,
            );
        }

        return $shipping;
    }

    /**
     * Return the billing address formatted
     *
     * @return string
     */
    public function billingAddress(): string
    {
        $billing = '';

        if ($this->billing) {
            $billing = sprintf(
                '<p>%s %s<br>%s<br>%s %s %s</p>',
                $this->billing->first_name,
                $this->billing->last_name,
                $this->billing->address_1,
                $this->billing->city,
                $this->billing->state,
                $this->billing->postcode,
            );
        }

        return $billing;
    }

    /**
     * Get Payment Method Name
     *
     * @return string
     */
    public function getPaymentMethodName(): string
    {
        if ($this->payment_id) {
            return $this->paymentMethod->title;
        }

        return 'Free';
    }

    /**
     * Get ORder Date Completed
     *
     * @return string
     */
    public function getDateCompleted(): string
    {
        if ($this->date_created->diffInDays(\Carbon\Carbon::now()) > 1) {
            return $this->date_created->format('F j, Y');
        }

        return $this->date_created->diffForHumans();
    }

    /**
     * GEt Subtotal
     *
     * @return int
     */
    public function getSubtotal(): int
    {
        return $this->items()->sum('subtotal');
    }

    /**
     * Get Permalink on this app
     *
     * @return string
     */
    public function getPermalink(): string
    {
        return route('kinja.orders.show', [$this->order_id]);
    }

    /**
     * Get Permalink on client store
     *
     * @return string
     */
    public function getPermalinkOnStore(): string
    {
        return sprintf(
            '%s/wp-admin/post.php?post=%s&action=edit',
            env('CLIENT_DOMAIN', 'https://kindhumans.com'),
            $this->order_id
        );
    }

    /**
     * Get Coupon codes
     *
     * @return void
     */
    public function getCouponCodes()
    {
        $coupons = collect($this->coupon_lines);

        if ($coupons->count() === 0) {
            return '';
        }

        $coupons = $coupons->pluck('code')->toArray();

        return implode(', ', $coupons);
    }

    /**
     * Get Bucket Cause Donation
     *
     * @return Money
     */
    public function getBucketCauseDonation(): Money
    {
        $amount = $this->getMetaValue('1_donated_amount');
        $amount = is_null($amount) ? 0 : $amount;
        $forceDecimals = ! is_float($amount);

        return Money::USD($amount, $forceDecimals);
    }

    /**
     * Get MEmbership Donation
     *
     * @return Money
     */
    public function getMembershipDonation(): Money
    {
        $amount = $this->getMetaValue('kindness_donated_amount');
        $amount = is_null($amount) ? 0 : $amount;
        $forceDecimals = ! is_float($amount);

        return Money::USD($amount, $forceDecimals);
    }

    /**
     * Calculate and Assign Donations
     *
     * @return void
     */
    public function calculateDonations(): void
    {
        AssignOrderDonationAction::run($this->id);
    }

    /**
     * Find an order
     *
     * @param  string|int  $orderId
     * @return Order|null
     */
    public static function findByOrderId(string|int $orderId): Order|null
    {
        return self::whereOrderId($orderId)->first();
    }

    /**
     * Convert Order to Array
     *
     * @param  bool  $isSingle
     * @return array
     */
    public function toArray(bool $isSingle = false): array
    {
        $data = parent::toArray();
        $data['date'] = $this->getDateCompleted();
        $data['sub_total'] = $this->getSubtotal();

        if ($isSingle) {
            $data['customer'] = $this->customer;
            $data['items'] = $this->items()->with('product')->get();
            $data['shipping_address'] = $this->shippingAddress();
            $data['billing_address'] = $this->billingAddress();
        }

        return $data;
    }

    /**
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return sprintf('%s_woocommerce_orders_index', env('MEILISEARCH_PREFIX', 'dev'));
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        $array = $this->toArray();

        $customer = $this->customer ? [
            'customer_id' => $this->customer->id,
            'name' => $this->customer->getFullName(),
            'email' => $this->customer->email,
        ] : [
            'id' => 0,
            'name' => $this->billing ? sprintf('%s %s', $this->billing->first_name, $this->billing->last_name) : '',
            'email' => $this->billing ? $this->billing->email : '',
        ];

        $array['customer'] = $customer;
        $array['date_created'] = $this->date_created ? $this->date_created->format('F j, Y') : '';

        return $array;
    }

    public function syncWithWoo() {
        SingleWooCommerceSync::dispatch($this->order_id, 'orders');
    }

    /**
     * Modify the query used to retrieve models when making all of the models searchable.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function makeAllSearchableUsing($query)
    {
        return $query->with(['customer', 'items']);
    }

    public function routeNotificationForSlack($notification)
    {
        return env('SLACK_CHANNEL_URL', 'https://hooks.slack.com/services/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');
    }
}
