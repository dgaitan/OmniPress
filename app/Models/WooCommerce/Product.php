<?php

namespace App\Models\WooCommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\ProductSetting;
use App\Casts\MetaData;

class Product extends Model
{
    use HasFactory;

    protected $casts = [
        'price' => 'decimal:0',
        'regular_price' => 'decimal:0',
        'sale_price' => 'decimal:0',
        'settings' => ProductSetting::class,
        'meta_data' => MetaData::class
    ];

    protected $fillable = [
        'product_id',
        'parent_id',
        'name',
        'slug',
        'permalink',
        'sku',
        'date_created',
        'date_modified',
        'type',
        'status',
        'featured',
        'on_sale',
        'purchasable',
        'virtual',
        'manage_stock',
        'stock_quantity',
        'stock_status',
        'sold_individually',
        'price',
        'regular_price',
        'sale_price',
        'settings',
        'meta_data'
    ];

    public function children() {
        $this->belongsTo(self::class, 'parent_id');
    }
}
