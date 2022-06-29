<?php

namespace App\Models\Causes;

use App\Models\WooCommerce\Order;
use App\Models\Concerns\HasMoney;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Causes\OrderDonation
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $order_id
 * @property int $cause_id
 * @property int $amount
 * @property-read \App\Models\Causes\Cause|null $cause
 * @property-read Order|null $order
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDonation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDonation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDonation query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDonation whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDonation whereCauseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDonation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDonation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDonation whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDonation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderDonation extends Model
{
    use HasFactory;
    use HasMoney;

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'cause_id',
        'order_id',
        'amount'
    ];

    /**
     * Order
     *
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Cause related
     *
     * @return BelongsTo
     */
    public function cause(): BelongsTo {
        return $this->belongsTo(Cause::class);
    }
}
