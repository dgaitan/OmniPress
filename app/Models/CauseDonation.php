<?php

namespace App\Models;

use App\Models\Causes\Cause;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\CauseDonation
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $cause_id
 * @property string $from
 * @property string $to
 * @property int $amount
 * @property int $total_orders
 * @method static \Illuminate\Database\Eloquent\Builder|CauseDonation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CauseDonation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CauseDonation query()
 * @method static \Illuminate\Database\Eloquent\Builder|CauseDonation whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CauseDonation whereCauseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CauseDonation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CauseDonation whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CauseDonation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CauseDonation whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CauseDonation whereTotalOrders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CauseDonation whereUpdatedAt($value)
 * @property-read Cause|null $cause
 * @mixin \Eloquent
 */
class CauseDonation extends Model
{
    use HasFactory;

    /**
     * Fillable fields
     *
     * @var string[]
     */
    protected $fillable = [
        'cause_id',
        'from',
        'to',
        'amount',
        'total_orders',
    ];

    /**
     * Field casts
     *
     * @var string[]
     */
    protected $casts = [
        'from' => 'date',
        'to' => 'date',
    ];

    /**
     * Cause related
     *
     * @return BelongsTo
     */
    public function cause(): BelongsTo
    {
        return $this->belongsTo(Cause::class, 'cause_id');
    }

    /**
     * Add donation to this donation period
     *
     * @param  int|float|string  $amount
     * @return CauseDonation
     */
    public function addDonation(int|float|string $amount): CauseDonation
    {
        if (is_float($amount) || is_string($amount)) {
            $amount = (int) ((float) $amount * 100);
        }

        $this->amount += $amount;
        $this->total_orders += 1;
        $this->save();

        return $this;
    }
}
