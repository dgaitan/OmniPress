<?php

namespace App\Models\WooCommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WooCommerce\WooSettingOption
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $setting_option_id
 * @property string $label
 * @property string|null $dscription
 * @property mixed $value
 * @property mixed $default
 * @property string $tip
 * @property string $placeholder
 * @property string $type
 * @property mixed $options
 * @property int $group_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereDscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption wherePlaceholder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereSettingOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereTip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereValue($value)
 * @mixin \Eloquent
 */
class WooSettingOption extends Model
{
    use HasFactory;
}
