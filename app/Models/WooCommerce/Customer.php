<?php

namespace App\Models\WooCommerce;

use App\Models\Causes\UserDonation;
use App\Models\Concerns\HasMetaData;
use App\Models\Membership;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

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
 * @property-read \Illuminate\Database\Eloquent\Collection|UserDonation[] $donations
 * @property-read int|null $donations_count
 */
class Customer extends Model
{
    use HasFactory;
    use Notifiable;
    use Billable;
    use HasMetaData;

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
        'date_modified' => 'datetime',
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
        'meta_data',
    ];

    /**
     * Relation with order
     *
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Customer Memberships
     *
     * @return HasMany
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * Customer donation
     *
     * @return HasMany
     */
    public function donations(): HasMany
    {
        return $this->hasMany(UserDonation::class, 'customer_id');
    }

    /**
     * This customer has an active membership?
     *
     * @return bool
     */
    public function hasMemberships(): bool
    {
        return $this->memberships()
            ->where('status', '!=', 'expired')
            ->exists();
    }

    /**
     * Retrieve the current membership
     *
     * @return Membership
     */
    public function membership(): Membership
    {
        return $this->memberships()
            ->where('status', '!=', 'expired')
            ->first();
    }

    /**
     * [getfullName description]
     *
     * @return [type] [description]
     */
    public function getfullName()
    {
        return sprintf('%s %s', $this->first_name, $this->last_name);
    }

    /**
     * Get Date Created
     *
     * @return string
     */
    public function getDateCreated(): string
    {
        if (! $this->date_created) {
            return '';
        }

        if ($this->date_created->diffInDays(\Carbon\Carbon::now()) > 1) {
            return $this->date_created->format('F j, Y');
        }

        return $this->date_created->diffForHumans();
    }

    /**
     * Get Permalink on client store
     *
     * @return string
     */
    public function getPermalinkOnStore(): string
    {
        return sprintf(
            '%s/wp-admin/user-edit.php?user_id=%s',
            env('CLIENT_DOMAIN', 'https://kindhumans.com'),
            $this->customer_id
        );
    }

    /**
     * Convert Model To Array
     *
     * @param  bool  $isSingle
     * @return array
     */
    public function toArray($isSingle = false): array
    {
        $data = parent::toArray();

        if (! $isSingle) {
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
     * Get payment methods and assign it
     *
     * @return void
     */
    public function setPaymentMethodsFromCustomerId()
    {
        $stripCustomerId = $this->getMetaValue('_stripe_customer_id', null);

        if (! is_null($stripCustomerId)) {
            try {
                // Try retrieve the customer to avoid
                $this->stripe()->customers->retrieve($stripCustomerId, []);
                $this->stripe_id = $stripCustomerId;
                $this->save();

                $paymentMethods = $this->stripe()->customers->allPaymentMethods(
                    $stripCustomerId,
                    ['type' => 'card']
                );

                if ($paymentMethods) {
                    foreach ($paymentMethods as $paymentMethod) {
                        $this->addPaymentMethod($paymentMethod->id);
                    }
                }
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                $this->stripe_id = null;
                $this->save();

                return $e->getMessage();
            } catch (\Stripe\Exception\InvalidArgumentException $e) {
                $this->stripe_id = null;
                $this->save();

                return $e->getMessage();
            }
        }

        return 'Yes';
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
     * @param  string  $token [description]
     * @return array - The new payment method resume
     */
    public function addAndAssignDefaultPaymentMethod(string $token): array
    {
        $paymentMethod = $this->addPaymentMethod($token);
        $defaultCard = $this->updateDefaultPaymentMethod($paymentMethod->id);

        return [
            'card' => self::getCardResume($paymentMethod),
            'payment_method' => $paymentMethod->toArray(),
        ];
    }

    /**
     * Get Card Resume
     *
     * @param  [type] $paymentMethod [description]
     * @return [type]                [description]
     */
    public static function getCardResume($paymentMethod)
    {
        return [
            'brand' => $paymentMethod->card->brand,
            'exp_month' => $paymentMethod->card->exp_month,
            'exp_year' => $paymentMethod->card->exp_year,
            'last4' => $paymentMethod->card->last4,
        ];
    }

    /**
     * Get WP Roles
     *
     * @return array
     */
    public static function getRoles(): array
    {
        $roles = [];
        $_roles = [
            'administrator' => 'Administrator',
            'editor' => 'Editor',
            'subscriber' => 'Subscriber',
            'customer' => 'Customer',
            'shop_manager' => 'Shop Manager',
            'affiliate' => 'Affiliate',
            'kindhumans_dropship_supplier' => 'Dropship Supplier',
        ];

        foreach ($_roles as $key => $value) {
            $roles[] = ['slug' => $key, 'label' => $value];
        }

        return $roles;
    }

    public static function getByEmail(string $email)
    {
        return self::whereEmail($email)->first();
    }
}
