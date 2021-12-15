<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ServiceType;
use App\Data\Service\WooCommerceAccessData;

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

    public function getAccessAttribute(string $access) {
        if ($this->type === ServiceType::WOOCOMMERCE) {
            return WooCommerceAccessData::fromEncryptedData($access);
        }

        return $access;
    }
}
