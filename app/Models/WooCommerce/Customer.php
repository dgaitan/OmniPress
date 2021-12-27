<?php

namespace App\Models\WooCommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\DataCollection;
use App\Data\Customer\MetaData;
use App\Data\Shared\AddressData;
use App\Helpers\Models\Jsonable;
use App\Models\Service;

/**
 * App\Models\WooCommerce\Customer
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $customer_id
 * @property \Illuminate\Support\Carbon|null $date_created
 * @property \Illuminate\Support\Carbon|null $date_modified
 * @property string $email
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $role
 * @property string $username
 * @property AddressData $billing
 * @property AddressData $shipping
 * @property bool $is_paying_customer
 * @property string|null $avatar_url
 * @property DataCollection $meta_data
 * @property int|null $service_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\Order[] $orders
 * @property-read int|null $orders_count
 * @property-read Service|null $service
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereBilling($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereDateCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereDateModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereIsPayingCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereMetaData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUsername($value)
 * @mixin \Eloquent
 */
class Customer extends Model
{
    use HasFactory, Jsonable;

    protected $casts = [
        'meta_data' => 'array',
        'billing' => 'array',
        'shipping' => 'array',
        'date_created' => 'datetime',
        'date_modified' => 'datetime'
    ];

    protected $fillable = [
        'customer_id',
        'date_created',
        'date_modified',
        'email',
        'first_name',
        'last_name',
        'role',
        'username',
        'billing',
        'shipping',
        'is_paying_customer',
        'avatar_url',
        'meta_data'
    ];

    /**
     * Relation with order
     * 
     * @return Order
     */
    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function service() {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * 
     */
    public function getMetaDataAttribute($meta_data) : DataCollection {
        return $this->getDataCollectionFrom(MetaData::class, $meta_data);
    }

    public function setMetaDataAttribute($meta_data) {
        $this->attributes['meta_data'] = $this->getCollectionJson(
            MetaData::class, $meta_data
        );
    }

    public function getShippingAttribute($shipping) : AddressData {
        return $this->getAddressData($shipping);
    }

    public function setShippingAttribute($shipping) {
        $this->attributes['shipping'] = $this->getAddressDataJson($shipping);
    }

    public function getBillingAttribute($billing) : AddressData {
        return $this->getAddressData($billing);
    }

    public function setBillingAttribute($billing) {
        $this->attributes['billing'] = $this->getAddressDataJson($billing);
    }
}
