<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ServiceType;
use App\Data\Service\WooCommerceAccessData;
use App\Models\WooCommerce\Customer;

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
    public function wooCustomers() {
        return $this->hasMany(Customer::class, 'service_id');
    }

    public function getAccessAttribute(string $access) {
        if ($this->type === ServiceType::WOOCOMMERCE) {
            return WooCommerceAccessData::fromEncryptedData($access);
        }

        return $access;
    }
}
