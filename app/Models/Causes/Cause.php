<?php

namespace App\Models\Causes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Causes\Cause
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $cause_id
 * @property string|null $name
 * @property string|null $cause_type
 * @property string|null $image_url
 * @property string|null $beneficiary
 * @method static \Illuminate\Database\Eloquent\Builder|Cause newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cause newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cause query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cause whereBeneficiary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cause whereCauseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cause whereCauseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cause whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cause whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cause whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cause whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cause whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Cause extends Model
{
    use HasFactory;

    protected $fillable = [
        'cause_id',
        'name',
        'cause_type',
        'image_url',
        'beneficiary'
    ];
}
