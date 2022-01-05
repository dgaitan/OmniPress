<?php

namespace App\Models\WooCommerce;

use App\Casts\CouponSetting;
use App\Casts\MetaData;
use App\Data\Coupon\CouponSettingData;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WooCommerce\Coupon
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $coupon_id
 * @property string $code
 * @property string $amount
 * @property \Illuminate\Support\Carbon $date_created
 * @property \Illuminate\Support\Carbon $date_modified
 * @property string $discount_type
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $date_expires
 * @property int $usage_count
 * @property bool $individual_use
 * @property mixed $settings
 * @property array|null $meta_data
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon query()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDateCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDateExpires($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDateModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereIndividualUse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereMetaData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereUsageCount($value)
 * @mixin \Eloquent
 * @property int|null $service_id
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereServiceId($value)
 */
class Coupon extends Model
{
    use HasFactory;

    protected $casts = [
        'date_created' => 'datetime',
        'date_modified' => 'datetime',
        'date_expires' => 'datetime',
        'meta_data' => MetaData::class,
        'settings' => CouponSetting::class
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

    public function service() {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
