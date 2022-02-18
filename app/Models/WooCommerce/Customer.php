<?php

namespace App\Models\WooCommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Cashier\Billable;
use App\Models\Membership;

/**
 * App\Models\WooCommerce\Customer
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $customer_id
 * @property \Illuminate\Support\Carbon|null $date_created
 * @property \Illuminate\Support\Carbon|null $date_modified
 * @property string $email
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $role
 * @property string $username
 * @property AddressData $billing
 * @property AddressData $shipping
 * @property bool $is_paying_customer
 * @property string|null $avatar_url
 * @property DataCollection|null $meta_data
 * @property int|null $service_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\Order[] $orders
 * @property-read int|null $orders_count
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereBilling($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereDateCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereDateModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereIsPayingCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereMetaData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUsername($value)
 * @mixin \Eloquent
 * @property string|null $stripe_id
 * @property string|null $pm_type
 * @property string|null $pm_last_four
 * @property string|null $trial_ends_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Cashier\Subscription[] $subscriptions
 * @property-read int|null $subscriptions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePmLastFour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePmType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereTrialEndsAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|Membership[] $memberships
 * @property-read int|null $memberships_count
 */
class Customer extends Model
{
    use HasFactory;
    use Notifiable;
    use Billable;

    /**
     * Model Casts
     *
     * @var array
     */
    protected $casts = [
        'meta_data' => 'array',
        'billing' => 'object',
        'shipping' => 'object',
        'date_created' => 'datetime',
        'date_modified' => 'datetime'
    ];

    /**
     * Model Fillables
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'date_created',
        'date_modified',
        'email',
        'first_name',
        'last_name',
        'role',
        'username',
        'billing',
        'shipping',
        'is_paying_customer',
        'avatar_url',
        'meta_data'
    ];

    /**
     * Relation with order
     *
     * @return HasMany
     */
    public function orders(): HasMany {
        return $this->hasMany(Order::class);
    }

    /**
     * Customer Memberships
     *
     * @return HasMany
     */
    public function memberships(): HasMany {
        return $this->hasMany(Membership::class);
    }

    /**
     * This customer has an active membership?
     *
     * @return boolean
     */
    public function hasMemberships(): bool {
        return $this->memberships()
            ->where('status', '!=', 'expired')
            ->exists();
    }

    /**
     * Retrieve the current membership
     *
     * @return Membership
     */
    public function membership(): Membership {
        return $this->memberships()
            ->where('status', '!=', 'expired')
            ->first();
    }

    /**
     * [getfullName description]
     * @return [type] [description]
     */
    public function getfullName() {
        return sprintf('%s %s', $this->first_name, $this->last_name);
    }

    /**
     * Convert Model To Array
     *
     * @param boolean $isSingle
     * @return array
     */
    public function toArray($isSingle = false): array {
        $data = parent::toArray();

        if (!$isSingle) {
            unset($data['shipping']);
            unset($data['billing']);
            unset($data['meta_data']);
        }

        unset($data['stripe_id']);
        unset($data['pm_type']);
        unset($data['pm_last_four']);
        unset($data['trial_ends_at']);

        $data['has_payment_method'] = $this->hasDefaultPaymentMethod();

        if ($data['has_payment_method']) {
            $data['default_payment_method'] = self::getCardResume(
                $this->defaultPaymentMethod()
            );
        }

        return $data;
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        // Return email address only...
        return $this->email;

        // Return email address and name...
        return [$this->email => $this->getFullName()];
    }

    /**
     * Add a new payment method and assign it as default payment method.
     *
     * @param string $token [description]
     * @return array - The new payment method resume
     */
    public function addAndAssignDefaultPaymentMethod(string $token): array {
        $paymentMethod = $this->addPaymentMethod($token);
        $defaultCard = $this->updateDefaultPaymentMethod($paymentMethod->id);

        return [
            'card' => self::getCardResume($paymentMethod),
            'payment_method' => $paymentMethod->toArray()
        ];
    }

    /**
     * Get Card Resume
     *
     * @param  [type] $paymentMethod [description]
     * @return [type]                [description]
     */
    public static function getCardResume($paymentMethod) {
        return [
            'brand' => $paymentMethod->card->brand,
            'exp_month' => $paymentMethod->card->exp_month,
            'exp_year' => $paymentMethod->card->exp_year,
            'last4' => $paymentMethod->card->last4
        ];
    }
}
