<?php

namespace App\Models;

use App\Data\Service\WooCommerceAccessData;
use App\Enums\ServiceType;
use App\Models\WooCommerce\Coupon;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Service
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property int $creator_id
 * @property int $organization_id
 * @property string|null $description
 * @property string $type
 * @property string|null $access
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|Customer[] $customers
 * @property-read int|null $customers_count
 * @property-read \App\Models\Organization $organization
 * @method static \Illuminate\Database\Eloquent\Builder|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|Customer[] $wooCustomers
 * @property-read int|null $woo_customers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Coupon[] $wooCoupons
 * @property-read int|null $woo_coupons_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Order[] $wooOrders
 * @property-read int|null $woo_orders_count
 */
class Service extends Model
{
    use HasFactory;

    /**
     * Fillable attributes
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'creator_id',
        'organization_id',
        'type',
        'description'
    ];

    /**
     * Creator of this service
     */
    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Orginzation which this service belongs to
     */
    public function organization() {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * Retrieve the woo customers related to this service.
     */
    public function customers() {
        if ($this->type === ServiceType::WOOCOMMERCE) {
            return $this->hasMany(Customer::class, 'service_id');
        }

        return null;
    }

    /**
     * Retrieve the woo coupons related to this service
     * 
     * @return \Illuminate\Database\Eloquent\Collection|Coupon[]
     */
    public function coupons() {
        if ($this->type === ServiceType::WOOCOMMERCE) {
            return $this->hasMany(Coupon::class, 'service_id');
        }

        return null;
    }

    /**
     * Collection of WooCommerce Orders related to this Service
     * 
     * @return \Illuminate\Database\Eloquent\Collection|Order[]
     */
    public function orders() {
        if ($this->type === ServiceType::WOOCOMMERCE) {
            return $this->hasMany(Order::class, 'service_id');
        }

        return null;
    }

    /**
     * Produdts related to this service
     * 
     * @return mixed
     */
    public function products() {
        if ($this->type === ServiceType::WOOCOMMERCE) {
            return $this->hasMany(Product::class, 'service_id');
        }

        return null;
    }

    public function getAccessAttribute(string $access) {
        if ($this->type === ServiceType::WOOCOMMERCE) {
            return WooCommerceAccessData::fromEncryptedData($access);
        }

        return $access;
    }
}
