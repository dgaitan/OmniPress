<?php

namespace App\Models\WooCommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;
use Illuminate\Notifications\Notifiable;
use App\Observers\OrderObserver;
use App\Models\Concerns\HasMetaData;

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
 */
class Order extends Model
{
    use HasFactory;
    use Notifiable;
    use HasMetaData;

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
        'total_tax' => 'decimal:0',
        'billing' => 'object',
        'shipping' => 'object',
        'meta_data' => 'array',
        'tax_lines' => 'array',
        'shipping_lines' => 'array',
        'fee_lines' => 'array',
        'coupon_lines' => 'array',
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
        'membership_id'
    ];

    /**
     * An order can have a customer, yes
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * An order should belongs to a payment method.
     *
     * @return BelongsTo
     */
    public function paymentMethod(): BelongsTo {
        return $this->belongsTo(PaymentMethod::class, 'payment_id');
    }

    /**
     * Order Items
     *
     * @return HasMany
     */
    public function items(): HasMany {
        return $this->hasMany(OrderLine::class, 'order_id');
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
    public function shippingAddress():string {
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
    public function billingAddress():string {
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

    public function getPaymentMethodName(): string {
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
    public function getDateCompleted(): string {
        if ($this->date_created->diffInDays(\Carbon\Carbon::now()) > 1) {
            return $this->date_created->format('F j, Y');
        }

        return $this->date_created->diffForHumans();
    }

    /**
     * GEt Subtotal
     *
     * @return integer
     */
    public function getSubtotal(): int {
        return $this->total - ((int) $this->total_tax + (int) $this->shipping_total) + $this->discount_total;
    }

    /**
     * Get Permalink on this app
     *
     * @return string
     */
    public function getPermalink(): string {
        return route('kinja.orders.show', [$this->order_id]);
    }

    /**
     * Get Permalink on client store
     *
     * @return string
     */
    public function getPermalinkOnStore(): string {
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
    public function getCouponCodes() {
        $coupons = collect($this->coupon_lines);

        if ($coupons->count() === 0) {
            return '';
        }

        $coupons = $coupons->pluck('code')->toArray();

        return implode(', ', $coupons);
    }

    /**
     * Convert Order to Array
     *
     * @param boolean $isSingle
     * @return array
     */
    public function toArray(bool $isSingle = false): array {
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
            'email' => $this->customer->email
        ] : [
            'id' => 0,
            'name' => $this->billing ? sprintf('%s %s', $this->billing->first_name, $this->billing->last_name) : '',
            'email' => $this->billing ? $this->billing->email : ''
        ];

        $array['customer'] = $customer;
        $array['date_created'] = $this->date_created ? $this->date_created->format('F j, Y') : '';

        return $array;
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
