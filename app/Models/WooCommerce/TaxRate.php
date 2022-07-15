<?php

namespace App\Models\WooCommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WooCommerce\TaxRate
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $tax_rate_id
 * @property string $country
 * @property string|null $postcode
 * @property string|null $city
 * @property mixed|null $postcodes
 * @property mixed|null $cities
 * @property string|null $rate
 * @property string|null $name
 * @property int $priority
 * @property bool $compound
 * @property bool $shipping
 * @property string $class
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate query()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereCities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereCompound($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate wherePostcodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereTaxRateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereUpdatedAt($value)
 * @mixin \Eloquent
 *
 * @property int|null $service_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereServiceId($value)
 */
class TaxRate extends Model
{
    use HasFactory;
}
