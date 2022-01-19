<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Sync
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $description
 * @property int $user_id
 * @property string $status
 * @property array $info
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Sync newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sync newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sync query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereUserId($value)
 * @mixin \Eloquent
 */
class Sync extends Model
{
    use HasFactory;

    const COMPLETED = 'completed';

    const PENDING = 'pending';

    const FAILED = 'failed';

    protected $casts = [
        'created_at' => 'datetime',
        'info' => 'array'
    ];

    protected $fillable = ['description', 'user_id', 'info', 'status'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
