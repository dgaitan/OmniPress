<?php

namespace App\Models\WooCommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\Address;
use App\Casts\MetaData;

class Order extends Model
{
    use HasFactory;

    protected $casts = [
        'date_created' => 'datetime',
        'date_modified' => 'datetime',
        'shipping_total' => 'decimal:0',
        'discount_total' => 'decimal:0',
        'discount_tax' => 'decimal:0',
        'shipping_tax' => 'decimal:0',
        'total' => 'decimal:0',
        'total_tax' => 'decimal:0',
        'billing' => Address::class,
        'shipping' => Address::class,
        'meta_data' => MetaData::class
    ];

    protected $fillable = [
        'order_id',
        'parent_id',
        'number',
        'order_key',
        'created_via',
        'version',
        'status',
        'currency',
        'date_created',
        'date_modified',
        'discount_total',
        'discount_tax',
        'shipping_total',
        'shipping_tax',
        'cart_tax',
        'total',
        'total_tax',
        'prices_include_tax',
        'customer_ip_address',
        'customer_user_agent',
        'transaction_id',
        'date_paid',
        'date_completed',
        'cart_hash',
        'set_paid',
        'meta_data',
        'billing',
        'shipping',
        'customer_id'
    ];

    /**
     * An order can have a customer, yes
     */
    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}
