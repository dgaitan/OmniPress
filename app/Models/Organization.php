<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_default', 'status', 'owner_id'];

    /**
     * An organization has an owner
     */
    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Add member relations
     */
    public function members() {
        return $this->belongsToMany(
            User::class,
            'organization_members',
            'organization_id',
            'user_id'
        );
    }
}
