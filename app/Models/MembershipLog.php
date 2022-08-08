<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MembershipLog
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $membership_id
 * @property string|null $description
 * @property int|null $customer_id
 * @property int|null $user_id
 * @property int|null $order_id
 * @property-read \App\Models\Membership|null $membership
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipLog whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipLog whereMembershipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipLog whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipLog whereUserId($value)
 * @mixin \Eloquent
 */
class MembershipLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'description', 'customer_id', 'user_id', 'order_id',
    ];

    /**
     * [membership description]
     *
     * @return [type] [description]
     */
    public function membership()
    {
        return $this->belongsTo(Membership::class, 'membership_id');
    }
}
