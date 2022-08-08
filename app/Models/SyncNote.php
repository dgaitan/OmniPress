<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SyncNote
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $sync_id
 * @property int $user_id
 * @property string|null $note
 * @method static \Illuminate\Database\Eloquent\Builder|SyncNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncNote query()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncNote whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncNote whereSyncId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncNote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncNote whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Sync|null $sync
 * @property-read \App\Models\User|null $user
 */
class SyncNote extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $fillable = ['user_id', 'note'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sync()
    {
        return $this->belongsTo(Sync::class, 'sync_id');
    }
}
