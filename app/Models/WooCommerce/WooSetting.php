<?php

namespace App\Models\WooCommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WooCommerce\WooSetting
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $setting_id
 * @property string|null $label
 * @property string $description
 * @property int|null $parent_id
 * @property string|null $sub_groups
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting whereSettingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting whereSubGroups($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting whereUpdatedAt($value)
 * @property int|null $service_id
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting whereServiceId($value)
 * @mixin \Eloquent
 */
class WooSetting extends Model
{
    use HasFactory;
}
