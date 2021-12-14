<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    /**
     * An organization has an owner
     */
    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
