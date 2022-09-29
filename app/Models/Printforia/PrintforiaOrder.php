<?php

namespace App\Models\Printforia;

use App\Actions\Printforia\CancelOrder;
use App\Mail\Printforia\OrderShipped;
use App\Models\WooCommerce\Order;
use App\Services\Printforia\PrintforiaApiClient;
use App\Services\Printforia\PrintforiaService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Mail;

/**
 * App\Models\Printforia\PrintforiaOrder
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $order_id
 * @property string|null $customer_reference
 * @property object|null $ship_to_address
 * @property object|null $return_to_address
 * @property string|null $shipping_method
 * @property string|null $ioss_number
 * @property string $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereCustomerReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereIossNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereReturnToAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereShipToAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereShippingMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 *
 * @property string $printforia_order_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Printforia\PrintforiaOrderItem[] $items
 * @property-read int|null $items_count
 * @property-read Order|null $order
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder wherePrintforiaOrderId($value)
 *
 * @property string|null $carrier
 * @property string|null $tracking_number
 * @property string|null $tracking_url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Printforia\PrintforiaOrderNote[] $notes
 * @property-read int|null $notes_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereCarrier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereTrackingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrder whereTrackingUrl($value)
 */
class PrintforiaOrder extends Model
{
    use HasFactory;

    const UNAPPROVED = [
        'slug' => 'unapproved', 'label' => 'Unapproved',
    ];

    const APPROVED = [
        'slug' => 'approved', 'label' => 'Approved',
    ];

    const SHIPPED = [
        'slug' => 'shipped', 'label' => 'Shipped',
    ];

    const IN_PROGRESS = [
        'slug' => 'in-progress', 'label' => 'In Progress',
    ];

    const COMPLETED = [
        'slug' => 'completed', 'label' => 'Completed',
    ];

    const REJECTED = [
        'slug' => 'rejected', 'label' => 'Rejected',
    ];

    const CANCELED = [
        'slug' => 'canceled', 'label' => 'Canceled',
    ];

    const STATUSES = [
        self::UNAPPROVED, self::APPROVED, self::SHIPPED, self::IN_PROGRESS,
        self::COMPLETED, self::REJECTED, self::CANCELED,
    ];

    /**
     * Field Casts
     *
     * @var string[]
     */
    protected $casts = [
        'ship_to_address' => 'object',
        'return_to_address' => 'object',
        'email_sent' => 'boolean'
    ];

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'customer_reference',
        'ship_to_address',
        'return_to_address',
        'shipping_method',
        'ioss_number',
        'status',
        'printforia_order_id',
        'email_sent',
        'tracking_number',
        'tracking_url',
        'carrier'
    ];

    /**
     * Order Related to this printforia order
     *
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Printforia items
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(PrintforiaOrderItem::class, 'order_id');
    }

    /**
     * Order Notes
     *
     * @return HasMany
     */
    public function notes(): HasMany
    {
        return $this->hasMany(PrintforiaOrderNote::class, 'order_id');
    }

    /**
     * Printforia Detail Permalink
     *
     * @return string
     */
    public function getPermalink(): string
    {
        return route('kinja.orders.printforiaDetail', [
            'id' => $this->printforia_order_id,
        ]);
    }

    /**
     * Printforia WooOrder Link
     *
     * @return string
     */
    public function getWooOrderPermalink(): string
    {
        return route('kinja.orders.show', [
            'id' => $this->order->order_id,
        ]);
    }

    /**
     * Return the shipping address formatted
     *
     * @return string
     */
    public function shippingAddress(): string
    {
        $shipping = '';

        if ($this->ship_to_address) {
            $shipping = sprintf(
                '<p>%s<br>%s %s<br>%s %s %s, %s</p>',
                $this->ship_to_address->recipient,
                $this->ship_to_address->address1,
                $this->ship_to_address->address2,
                $this->ship_to_address->city,
                $this->ship_to_address->region,
                $this->ship_to_address->postal_code,
                $this->ship_to_address->country_code
            );
        }

        return $shipping;
    }

    /* Return the return address formatted
    *
    * @return string
    */
    public function returnAddress(): string
    {
        $shipping = '';

        if ($this->return_to_address) {
            $shipping = sprintf(
               '<p>%s<br>%s %s<br>%s %s %s, %s</p>',
               $this->return_to_address->recipient,
               $this->return_to_address->address1,
               $this->return_to_address->address2,
               $this->return_to_address->city,
               $this->return_to_address->region,
               $this->return_to_address->postal_code,
               $this->return_to_address->country_code
           );
        }

        return $shipping;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getItemsAsWooItems()
    {
        return PrintforiaService::getOrderItemsHasWooCommerceItems($this);
    }

    /**
     * Send Order Has Shipped Email
     *
     * @return void
     */
    public function sendOrderHasShippedEmail(): void
    {
        Mail::to($this->ship_to_address->email)
            ->queue(new OrderShipped($this));
    }

    /**
     * Maybe Send Shipped Email
     *
     * @return void
     */
    public function maybeSendShippedEmail(): void {
        if ($this->isProcessed() && ! $this->email_sent) {
            $this->email_sent = true;
            $this->sendOrderHasShippedEmail();
        }
    }

    /**
     * IS printforia order processed?
     * 
     * It means that is shipped or completed
     *
     * @return boolean
     */
    public function isProcessed(): bool {
        return in_array($this->status, ['shipped', 'completed']);
    }

    /**
     * Cancell a printforia order locally and in the API
     *
     * @return void
     */
    public function cancelOrder(): void
    {
        CancelOrder::dispatch($this);
    }

    public static function getOrderFromApi(string $orderId)
    {
        return (new PrintforiaApiClient)->getOrder($orderId);
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public static function getStatusesSlugs(): array
    {
        return collect(self::STATUSES)->map(function ($status) {
            return $status['slug'];
        })->toArray();
    }

    /**
     * Get Return To Address
     *
     * @return array
     */
    public static function getReturnToAddress(): array
    {
        return [
            'recipient' => env('PRINTFORIA_RETURN_RECIPIENT', 'Returns Department'),
            'address1' => env('PRINTFORIA_RETURN_ADDRESS_1', ''),
            'address2' => '',
            'address3' => '',
            'city' => env('PRINTFORIA_RETURN_CITY', 'Encinitas'),
            'region' => env('PRINTFORIA_RETURN_REGION', 'CA'),
            'postal_code' => env('PRINTFORIA_RETURN_ZIP', '92024'),
            'country_code' => 'US',
        ];
    }
}
