<?php

namespace App\Models\PreSales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\PreSales\PreSaleProduct
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $product_name
 * @property int $woo_product_id
 * @property string $release_date
 * @property bool $is_active
 * @method static \Illuminate\Database\Eloquent\Builder|PreSaleProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PreSaleProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PreSaleProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|PreSaleProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreSaleProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreSaleProduct whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreSaleProduct whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreSaleProduct whereReleaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreSaleProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreSaleProduct whereWooProductId($value)
 * @mixin \Eloquent
 */
class PreSaleProduct extends Model {
    use HasFactory;

    /**
     * Field Casting
     *
     * @var array<string, mixed>
     */
    protected $casts = [
        'release_date' => 'date'
    ];

    /**
     * Fillable fields
     *
     * @var array<string>
     */
    protected $fillable = [
        'product_name',
        'woo_product_id',
        'release_date',
        'is_active'
    ];

    /**
     * Products related
     *
     * @return BelongsToMany
     */
    public function orders(): BelongsToMany {
        return $this->belongsToMany(
            PreOrder::class,
            'order_product_relation',
            'pre_sale_product_id',
            'pre_order_id'
        )->as('orders')->withTimestamps();
    }
}
