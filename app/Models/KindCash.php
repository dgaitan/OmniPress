<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\KindCashLog[] $logs
 * @property-read int|null $logs_count
 * @property-read \App\Models\Membership|null $membership
 */
class KindCash extends Model
{
    use HasFactory;

    protected $fillable = [
        'points', 'last_earned'
    ];

    public function membership() {
        return $this->belongsTo(Membership::class);
    }

    /**
     * Get kindcash readable for humans
     *
     * @return float
     */
    public function cashForHuman(): float {
        return (float) ($this->points / 100);
    }

    /**
     * [logs description]
     * @return [type] [description]
     */
    public function logs() {
        return $this->hasMany(KindCashLog::class, 'kind_cash_id')->orderBy('id', 'desc');
    }

    /**
     * [addRedeemedLog description]
     * @param int $points [description]
     */
    public function addRedeemedLog(int $points, string $description = '', $date = null) {
        $this->addLog('redeem', $points, $description, $date);
    }

    /**
     * [addInitialLog description]
     * @param string $description [description]
     */
    public function addInitialLog(string $description = '', $date = null) {
        $this->addLog('initialize', $this->points, $description, $date);
    }

    /**
     * [addEarnLog description]
     * @param int    $points      [description]
     * @param string $description [description]
     */
    public function addEarnLog(int $points, string $description = '', $date = null) {
        $this->addLog('earned', $points, $description, $date);
    }

    /**
     * [addLog description]
     * @param string $event       [description]
     * @param int    $points      [description]
     * @param string $description [description]
     */
    public function addLog(string $event, int $points, string $description = '', $date = null) {
        $this->logs()->create([
            'date' => $date ? (new \Carbon\Carbon(strtotime($date)))->toDateTimeString() : \Carbon\Carbon::now(),
            'event' => $event,
            'points' => $points,
            'description' => $description
        ]);
    }

    /**
     * [addOrderLog description]
     * @param int    $points   [description]
     * @param int    $order_id [description]
     * @param [type] $date     [description]
     */
    public function addOrderLog(int $points, int $order_id, $date = null) {
        $this->logs()->create([
            'date' => $date ? (new \Carbon\Carbon(strtotime($date)))->toDateTimeString() : \Carbon\Carbon::now(),
            'event' => 'earned',
            'points' => $points,
            'order_id' => $order_id,
            'description' => 'Earned by order purchase.'
        ]);
    }

    public function addCash(int $points, string $message) {
        $newPoints = $points + $this->points;

        $this->update([
            'points' => $newPoints,
            'last_earned' => $points
        ]);

        $this->addEarnLog($points, $message);
    }

    public function redeemCash(int $points, string $message, int $order_id) {
        $newPoints = (int) ($this->points - $points);

        $this->update([
            'points' => $newPoints
        ]);

        $this->logs()->create([
            'date' => \Carbon\Carbon::now(),
            'event' => 'redeem',
            'points' => $points,
            'order_id' => $order_id,
            'description' => $message
        ]);
    }

    /**
     * [toArray description]
     * @return [type] [description]
     */
    public function toArray($isSingle = false) {
        $cash = parent::toArray();
        $cash['last_earned'] = is_null($cash['last_earned']) ? 0 : $cash['last_earned'];
        $cash['points'] = is_null($cash['points']) ? 0 : $cash['points'];

        if ($isSingle) {
            $cash['logs'] = $this->logs;
        }

        unset($cash['membership_id']);

        return $cash;
    }

    /**
     * Handle Some actions on model boot
     *
     * @return void
     */
    protected static function boot() {
        parent::boot();

        // Forget Membership Cache
        static::saving(function() {
            Cache::tags('memberships')->flush();
        });
    }
}
