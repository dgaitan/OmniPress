<?php

namespace App\Models\WooCommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\DataCollection;
use App\Data\Customer\MetaData;
use App\Data\Shared\AddressData;
use App\Helpers\Models\Jsonable;

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
