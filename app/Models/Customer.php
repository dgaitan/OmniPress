<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Data\Customer\MetaData;
use Spatie\LaravelData\DataCollection;

class Customer extends Model
{
    use HasFactory;

    protected $casts = [
        'billing' => 'array',
        'shipping' => 'array',
        'meta_data' => 'array'
    ];

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

    /**
     * Relation with order
     * 
     * @return Order
     */
    public function orders() {
        return $this->hasMany(Order::class);
    }

    /**
     * Meta Data collection
     * 
     * @return DataCollection
     */
    public function getMeta() : DataCollection {
        return MetaData::collection( $this->meta_data );
    }
}
