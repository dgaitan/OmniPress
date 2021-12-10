<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'date_created',
        'date_modified',
        'email',
        'first_name',
        'last_name',
        'role',
        'username',
        'billing',
        'shipping',
        'is_paying_customer',
        'avatar_url',
        'meta_data'
    ];

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
