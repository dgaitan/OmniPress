<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
 * @property string $name
 * @property string $content_type
 * @property int|null $intents
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SyncLog[] $logs
 * @property-read int|null $logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SyncNote[] $notes
 * @property-read int|null $notes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereContentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereIntents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereName($value)
 */
class Sync extends Model
{
    use HasFactory;

    const COMPLETED = 'completed';

    const PENDING = 'pending';

    const FAILED = 'failed';

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'name', 'description', 
        'user_id', 'status',
        'content_type', 'intents', 'current_page'
    ];

    public function getContentTypeAttribute($value): string {
        return ucfirst($value);
    }

    /**
     * User triggered the sync
     * @return [type] [description]
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Notes related to this Sync
     * @return [type] [description]
     */
    public function notes() {
        return $this->hasMany(SyncNote::class, 'sync_id');
    }

    /**
     * Logs related to this Sync
     * @return [type] [description]
     */
    public function logs() {
        return $this->hasMany(SyncLog::class, 'sync_id');
    }

    /**
     * Add a new log
     * 
     * @param string $message [description]
     */
    public function add_log(string $message) {
        $this->logs()->create(['description' => $message]);
    }

    public function shouldStop(int $page = 1) {
        return ($this->current_page + 5) === $page;
    }

    public function complete() {
        $this->update(['status' => self::COMPLETED]);
        $this->add_log(sprintf(
            "Sync Completed by %s",
            Carbon::now()->format('F j, Y @ H:i:s')
        ));
    }

    /**
     * Initialize a new sync
     * @param  string $content_type [description]
     * @param  int    $user_id      [description]
     * @param  string $description  [description]
     * @return [type]               [description]
     */
    public static function initialize(
        string $content_type, 
        User $user, 
        ?string $description = ''
    ) {
        $sync = self::create([
            'name' => sprintf('%s sync', ucwords($content_type)),
            'status' => self::PENDING,
            'content_type' => $content_type,
            'user_id' => $user->id,
            'description' => $description,
            'intents' => 1,
            'current_page' => 1
        ]);

        $sync->add_log(sprintf(
            'Sync started by %s at %s',
            $user->name,
            Carbon::now()->format('F j, Y @ H:i:s')
        ));

        return $sync;
    }
}
