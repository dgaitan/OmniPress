<?php

namespace App\Models\WooCommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WooCommerce\ShippingZone
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $shipping_zone_id
 * @property string $title
 * @property int $order
 * @property mixed|null $locations
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereLocations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereShippingZoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShippingZone extends Model
{
    use HasFactory;
}
