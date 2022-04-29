<?php

namespace App\Models\Subscription;

use App\Models\WooCommerce\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionProduct extends Model
{
    use HasFactory;

    protected $casts = [
        'intervals' => 'object',
        'expiration_date' => 'date'
    ];

    protected $fillable = [
        'expiration_date',
        'price',
        'fee',
        'use_parent_settings',
        'intervals'
    ];

    /**
     * Product
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
