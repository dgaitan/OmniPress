<?php

namespace App\Models\WooCommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    use HasFactory;

    protected $casts = [
        'subtotal' => 'decimal:0',
        'subtotal_tax' => 'decimal:0',
        'total' => 'decimal:0',
        'taxes' => 'array',
        'price' => 'decimal:0'
    ];

    protected $fillable = [
        'order_line_id',
        'name',
        'product_id',
        'variation_id',
        'quantity',
        'tax_class',
        'subtotal',
        'subtotal_tax',
        'total',
        'taxes',
        'meta_data',
        'sku',
        'price'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
