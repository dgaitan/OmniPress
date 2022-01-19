<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\KindCashLog
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $kind_cash_id
 * @property string|null $event
 * @property string|null $date
 * @property int|null $order_id
 * @method static \Illuminate\Database\Eloquent\Builder|KindCashLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KindCashLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KindCashLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|KindCashLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindCashLog whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindCashLog whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindCashLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindCashLog whereKindCashId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindCashLog whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindCashLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class KindCashLog extends Model
{
    use HasFactory;
}
