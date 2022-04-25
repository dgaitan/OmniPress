<?php

namespace App\Models\Subscription;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Subscription\KindhumanSubscriptionLog
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $subscription_id
 * @property string|null $by
 * @property string $message
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionLog whereBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionLog whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionLog whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindhumanSubscriptionLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class KindhumanSubscriptionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'by',
        'message',
        'created_at'
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(KindhumanSubscription::class, 'subscription_id');
    }
}
