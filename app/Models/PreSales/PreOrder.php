<?php

namespace App\Models\PreSales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\PreSales\PreOrder
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $woo_order_id
 * @property string $customer_email
 * @property int $customer_id
 * @property string $status
 * @property \Illuminate\Support\Carbon $release_date
 * @property int $product_id
 * @property string $product_name
 * @property int $sub_total
 * @property int $taxes
 * @property int $shipping
 * @property int $total
 * @property bool $released
 * @method static \Illuminate\Database\Eloquent\Builder|PreOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PreOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PreOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|PreOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreOrder whereCustomerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreOrder whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreOrder whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreOrder whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreOrder whereReleaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreOrder whereReleased($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreOrder whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreOrder whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreOrder whereTaxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreOrder whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreOrder whereWooOrderId($value)
 * @mixin \Eloquent
 */
class PreOrder extends Model {
    use HasFactory;

    /**
     * Field casting
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
        'woo_order_id',
        'customer_email',
        'customer_id',
        'status',
        'release_date',
        'product_id',
        'product_name',
        'sub_total',
        'taxes',
        'shipping',
        'total',
        'released'
    ];

    /**
     * Products related
     *
     * @return BelongsToMany
     */
    public function products(): BelongsToMany {
        return $this->belongsToMany(
            PreSaleProduct::class,
            'order_product_relation',
            'pre_order_id',
            'pre_sale_product_id'
        )->as('products')->withTimestamps();
    }
}
