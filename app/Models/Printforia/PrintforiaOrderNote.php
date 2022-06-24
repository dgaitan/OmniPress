<?php

namespace App\Models\Printforia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Printforia\PrintforiaOrderNote
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $order_id
 * @property string $title
 * @property string|null $body
 * @property string|null $order_status_code
 * @property string|null $note_date
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderNote query()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderNote whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderNote whereNoteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderNote whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderNote whereOrderStatusCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderNote whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintforiaOrderNote whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PrintforiaOrderNote extends Model
{
    use HasFactory;

    /**
     * Field Casts
     *
     * @var array
     */
    protected $casts = [
        'note_date' => 'datetime',
    ];

    protected $fillable = [
        'order_id',
        'title',
        'body',
        'order_status_code',
        'note_date',
    ];

    /**
     * Printforia Order
     *
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(PrintforiaOrder::class, 'order_id');
    }
}
