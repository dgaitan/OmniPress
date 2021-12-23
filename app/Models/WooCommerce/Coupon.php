<?php

namespace App\Models\WooCommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Models\Jsonable;
use App\Data\Coupon\CouponSettingData;
use App\Casts\MetaData;

class Coupon extends Model
{
    use HasFactory, Jsonable;

    protected $casts = [
        'date_created' => 'datetime',
        'date_modified' => 'datetime',
        'date_expires' => 'datetime',
        'meta_data' => MetaData::class,
    ];

    protected $fillable = [
        'coupon_id',
        'code',
        'amount',
        'date_created',
        'date_modified',
        'discount_type',
        'description',
        'date_expires',
        'usage_count',
        'individual_use',
        'settings',
        'meta_data'
    ];

    public function getSettingsAttribute($settings) {
        return $this->getDataFrom(
            CouponSettingData::class, $settings
        );
    }

    public function setSettingsAttribute($settings) {
        $this->attributes['settings'] = $this->getDataJson(
            CouponSettingData::class, $settings
        );
    }
}
