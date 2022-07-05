<?php

namespace App\Models;

use Carbon\Carbon;
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
 *
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
 *
 * @property string $name
 * @property string $content_type
 * @property int|null $intents
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SyncLog[] $logs
 * @property-read int|null $logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SyncNote[] $notes
 * @property-read int|null $notes_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereContentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereIntents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereName($value)
 *
 * @property string|null $batch_id
 * @property int|null $current_page
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereCurrentPage($value)
 *
 * @property int|null $per_page
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Sync wherePerPage($value)
 */
class Sync extends Model
{
    use HasFactory;

    const COMPLETED = 'completed';

    const PENDING = 'pending';

    const FAILED = 'failed';

    const RESOURCES_TYPES = [
        'memberships',
        'customers',
        'products',
        'productVariations',
        'orders',
        'paymentMethods',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'name', 'description',
        'user_id', 'status', 'per_page',
        'content_type', 'intents', 'current_page',
    ];

    public function getContentTypeAttribute($value): string
    {
        return ucfirst($value);
    }

    /**
     * User triggered the sync
     *
     * @return [type] [description]
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Notes related to this Sync
     *
     * @return [type] [description]
     */
    public function notes()
    {
        return $this->hasMany(SyncNote::class, 'sync_id');
    }

    /**
     * Logs related to this Sync
     *
     * @return [type] [description]
     */
    public function logs()
    {
        return $this->hasMany(SyncLog::class, 'sync_id');
    }

    /**
     * Add a new log
     *
     * @param  string  $message [description]
     */
    public function add_log(string $message)
    {
        $this->logs()->create(['description' => $message]);
    }

    public function shouldStop(int $page = 1)
    {
        return ($this->current_page + env('KINDHUMANS_SYNC_PAGINATE', 10)) === $page;
    }

    /**
     * Is this sync completed?
     *
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->status === self::COMPLETED;
    }

    /**
     * Complete a sync
     *
     * @return void
     */
    public function complete()
    {
        $this->update(['status' => self::COMPLETED]);
        $this->add_log(sprintf(
            'Sync Completed by %s',
            Carbon::now()->format('F j, Y @ H:i:s')
        ));
    }

    /**
     * Initialize a new sync
     *
     * @param  string  $content_type [description]
     * @param  int  $user_id      [description]
     * @param  string  $description  [description]
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
            'current_page' => 1,
            'per_page' => env('KINDHUMANS_SYNC_PER_PAGE', 50),
        ]);

        $sync->add_log(sprintf(
            'Sync started by %s at %s',
            $user->name,
            Carbon::now()->format('F j, Y @ H:i:s')
        ));

        $sync->save();

        \App\Jobs\WooCommerceSyncServiceJob::dispatch($sync->id);

        return $sync;
    }

    /**
     * Find a Sync and resume it
     *
     * @param  int  $sync_id
     * @return void
     */
    public static function resume(int $sync_id): void
    {
        $sync = self::find($sync_id);
        $api = self::makeWooCommerceService();
        $api->{$sync->content_type}()
            ->syncAll($sync->per_page, $sync->current_page, $sync->id);
    }

    /**
     * Get WooCommerce APi
     *
     * @return \App\Services\WooCommerce\WooCommerceService
     */
    public static function makeWooCommerceService(): \App\Services\WooCommerce\WooCommerceService
    {
        return \App\Services\WooCommerce\WooCommerceService::make();
    }
}
