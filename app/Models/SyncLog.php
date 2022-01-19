<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SyncLog
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $sync_id
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|SyncLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncLog whereSyncId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SyncLog extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime'
    ];

    protected $fillable = ['description'];

    public function sync() {
        return $this->belongsTo(Sync::class, 'sync_id');
    }
}
