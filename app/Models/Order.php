<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function getDiscountTotalAttribute($price)
    {
        return $this->getPriceAmount($price);
    }

    public function setDiscountTotalAttribute($price)
    {
        $this->attributes['price'] = $this->setPriceAmount($price);
    }

    /**
     * Get price amoutn formatted
     * 
     * @param float|integer $price
     * @return integer
     */
    protected function getPriceAmount($price)
    {
        return $price / 100;
    }
    
    /**
     * Set price amount formatted
     * 
     * @param float|integer $price
     * @return integer
     */
    protected function setPriceAmount($price) {
        return $price * 100;
    }
}
