<?php

namespace App\Models\Causes;

use App\Models\CauseDonation;
use App\Services\DonationsService;
use Carbon\Carbon;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 * @property-read \Illuminate\Database\Eloquent\Collection|CauseDonation[] $donations
 * @property-read int|null $donations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Causes\OrderDonation[] $orderDonations
 * @property-read int|null $order_donations_count
 * @mixin \Eloquent
 */
class Cause extends Model
{
    use HasFactory;

    /**
     * Causes types
     *
     * @var array
     */
    public static $causeTypes = [
        'fixed' => 'Collab for Cause',
        'bucket' => 'Bucket Cause',
    ];

    protected $fillable = [
        'cause_id',
        'name',
        'cause_type',
        'image_url',
        'beneficiary',
    ];

    /**
     * Cause Donations
     *
     * @return HasMany
     */
    public function donations(): HasMany
    {
        return $this->hasMany(CauseDonation::class, 'cause_id');
    }

    /**
     * Order Donations
     *
     * @return HasMany
     */
    public function orderDonations(): HasMany
    {
        return $this->hasMany(OrderDonation::class);
    }

    /**
     * Get Period
     *
     * @param  Carbon|null|null  $date
     * @return CauseDonation
     */
    public function getPeriod(Carbon|null $date = null): CauseDonation
    {
        return DonationsService::getDonationPeriod($this, $date);
    }

    /**
     * Get Total Amount Donated in Dollars
     *
     * @param  bool  $lifetime
     * @param  Carbon|null|null  $from
     * @param  Carbon|null|null  $to
     * @return string
     */
    public function getTotalAmountDonatedInDollars(
        bool $lifetime = true,
        Carbon|null $from = null,
        Carbon|null $to = null
    ): string {
        return Money::USD($this->getTotalAmountDonated())->format();
    }

    /**
     * Get Total Donated for a giving period
     *
     * @param  Carbon|null|null  $from
     * @param  Carbon|null|null  $to
     * @return int
     */
    public function getTotalAmountDonated(
        bool $lifetime = true,
        Carbon|null $from = null,
        Carbon|null $to = null
    ): int {
        return DonationsService::getCauseFieldTotal(
            cause: $this,
            field: 'amount',
            lifetime: $lifetime,
            from: $from,
            to: $to
        );
    }

    /**
     * Get total orders
     *
     * @param  bool  $lifetime
     * @param  Carbon|null|null  $from
     * @param  Carbon|null|null  $to
     * @return int
     */
    public function getTotalOrders(
        bool $lifetime = true,
        Carbon|null $from = null,
        Carbon|null $to = null
    ): int {
        return DonationsService::getCauseFieldTotal(
            cause: $this,
            field: 'total_orders',
            lifetime: $lifetime,
            from: $from,
            to: $to
        );
    }

    /**
     * Get the cause type label
     *
     * @return string
     */
    public function getCauseType(): string
    {
        return self::$causeTypes[$this->cause_type];
    }

    /**
     * Get Image Url parsed
     *
     * @return string
     */
    public function getImage(): string
    {
        $imageUrl = '';

        if ($this->src) {
            $imageUrl = explode('wp-content/', $this->image_url);
            $imageUrl = sprintf(
                '%s/wp-content/%s',
                env('ASSET_DOMAIN', 'https://kindhumans.com'),
                end($imageUrl)
            );
        }

        return $imageUrl;
    }

    /**
     * Cause exists?
     *
     * @param  mixed  $causeId
     * @return bool
     */
    public static function causeExists(mixed $causeId): bool
    {
        return self::whereCauseId($causeId)->exists();
    }

    public static function findCause(mixed $causeId): self|null
    {
        return self::whereCauseId($causeId)->first();
    }

    /**
     * Get valid cause types
     *
     * @return array
     */
    public static function getValidCauseTypes(): array
    {
        return array_keys(self::$causeTypes);
    }

    /**
     * This is the cause types list
     *
     * @return array
     */
    public static function getCauseTypes(): array
    {
        $types = [];

        foreach (self::$causeTypes as $slug => $label) {
            $types[] = [
                'slug' => $slug,
                'label' => $label,
            ];
        }

        return $types;
    }
}
