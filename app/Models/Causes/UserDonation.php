<?php

namespace App\Models\Causes;

use App\Models\WooCommerce\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Causes\UserDonation
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $customer_id
 * @property int $cause_id
 * @property int $donation
 * @method static \Illuminate\Database\Eloquent\Builder|UserDonation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserDonation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserDonation query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserDonation whereCauseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDonation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDonation whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDonation whereDonation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDonation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDonation whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Causes\Cause|null $cause
 * @property-read Customer|null $customer
 */
class UserDonation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'cause_id',
        'donation'
    ];

    /**
     * Customer relationship
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Cause Related
     *
     * @return BelongsTo
     */
    public function cause(): BelongsTo
    {
        return $this->belongsTo(Cause::class, 'cause_id');
    }

    /**
     * Add donation
     *
     * @param integer|string|float $amount
     * @return self
     */
    public function addDonation(int|string|float $amount): self
    {
        if (is_float($amount) || is_string($amount)) {
            $amount = (int) ((float) $amount * 100);
        }

        $this->donation = $this->donation + $amount;
        $this->save();

        return $this;
    }
}
