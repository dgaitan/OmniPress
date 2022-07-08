<?php

namespace App\Models\WooCommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WooCommerce\ShippingMethod
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $shipping_method_id
 * @property string $title
 * @property int $order
 * @property bool $enabled
 * @property int|null $method_id
 * @property string|null $method_title
 * @property string|null $method_description
 * @property mixed|null $settings
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereMethodDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereMethodTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereShippingMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShippingMethod extends Model
{
    use HasFactory;
}
