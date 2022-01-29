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
     * [logs description]
     * @return [type] [description]
     */
    public function logs() {
        return $this->hasMany(KindCashLog::class, 'kind_cash_id');
    }

    /**
     * [addRedeemedLog description]
     * @param int $points [description]
     */
    public function addRedeemedLog(int $points) {
        $this->addLog('redeem', $points);
    }

    /**
     * [addInitialLog description]
     * @param string $description [description]
     */
    public function addInitialLog(string $description = '') {
        $this->addLog('initialize', $this->points, $description);
    }

    /**
     * [addEarnLog description]
     * @param int    $points      [description]
     * @param string $description [description]
     */
    public function addEarnLog(int $points, string $description = '') {
        $this->addLog('earned', $points, $description);
    }

    /**
     * [addLog description]
     * @param string $event       [description]
     * @param int    $points      [description]
     * @param string $description [description]
     */
    public function addLog(string $event, int $points, string $description = '') {
        $this->logs()->create([
            'date' => \Carbon\Carbon::now(),
            'event' => $event,
            'points' => $points,
            'description' => $description
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

    /**
     * [toArray description]
     * @return [type] [description]
     */
    public function toArray($isSingle = false) {
        $cash = parent::toArray();

        if ($isSingle) {
            $cash['logs'] = $this->logs;
        }
        
        unset($cash['membership_id']);

        return $cash;
    }
}
