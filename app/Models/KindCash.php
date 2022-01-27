<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\KindCash
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $membership_id
 * @property float $points
 * @property float|null $last_earned
 * @method static \Illuminate\Database\Eloquent\Builder|KindCash newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KindCash newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KindCash query()
 * @method static \Illuminate\Database\Eloquent\Builder|KindCash whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindCash whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindCash whereLastEarned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindCash whereMembershipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindCash wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindCash whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class KindCash extends Model
{
    use HasFactory;

    protected $fillable = [
        'points', 'last_earned'
    ];

    public function membership() {
        return $this->belongsTo(Membership::class, 'membership_id');
    }

    public function logs() {
        return $this->hasMany(KindCashLog::class, 'kind_cash_id');
    }
}
