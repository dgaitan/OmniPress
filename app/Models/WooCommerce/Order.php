<?php

namespace App\Models\WooCommerce;

use App\Casts\Address;
use App\Casts\MetaData;
use App\Casts\OrderLines;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 * @property-read Service|null $service
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
 */
class Order extends Model
{
    use HasFactory;

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
        'billing' => Address::class,
        'shipping' => Address::class,
        'meta_data' => MetaData::class,
        'tax_lines' => OrderLines::class,
        'shipping_lines' => OrderLines::class,
        'fee_lines' => OrderLines::class,
        'coupon_lines' => OrderLines::class,
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
        'coupon_lines'
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
     * Order Items
     *
     * @return HasMany
     */
    public function items(): HasMany {
        return $this->hasMany(OrderLine::class, 'order_id');
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
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'woocommerce_orders_index';
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
        return $query->with('customer');
    }
}
