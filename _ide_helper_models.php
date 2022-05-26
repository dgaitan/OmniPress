<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\Causes{
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
 */
	class Cause extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ConnectedAccount
 *
 * @property int $id
 * @property int $user_id
 * @property string $provider
 * @property string $provider_id
 * @property string|null $name
 * @property string|null $nickname
 * @property string|null $email
 * @property string|null $telephone
 * @property string|null $avatar_path
 * @property string $token
 * @property string|null $secret
 * @property string|null $refresh_token
 * @property string|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectedAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectedAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectedAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectedAccount whereAvatarPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectedAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectedAccount whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectedAccount whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectedAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectedAccount whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectedAccount whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectedAccount whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectedAccount whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectedAccount whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectedAccount whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectedAccount whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectedAccount whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectedAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectedAccount whereUserId($value)
 * @mixin \Eloquent
 */
	class ConnectedAccount extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\KindCash
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $membership_id
 * @property float $points
 * @property float|null $last_earned
 * @method static \Illuminate\Database\Eloquent\Builder|KindCash newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KindCash newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KindCash query()
 * @method static \Illuminate\Database\Eloquent\Builder|KindCash whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindCash whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindCash whereLastEarned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindCash whereMembershipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindCash wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindCash whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\KindCashLog[] $logs
 * @property-read int|null $logs_count
 * @property-read \App\Models\Membership|null $membership
 */
	class KindCash extends \Eloquent {}
}

namespace App\Models{
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
 * @property int|null $points
 * @property string|null $description
 * @property-read \App\Models\KindCash|null $kindCash
 * @method static \Illuminate\Database\Eloquent\Builder|KindCashLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KindCashLog wherePoints($value)
 */
	class KindCashLog extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Membership
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $customer_id
 * @property string $customer_email
 * @property \Illuminate\Support\Carbon $start_at
 * @property \Illuminate\Support\Carbon $end_at
 * @property string $price
 * @property string $shipping_status
 * @property string $status
 * @property int|null $pending_order_id
 * @property \Illuminate\Support\Carbon|null $last_payment_intent
 * @property int $payment_intents
 * @property int $kind_cash_id
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership query()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereCustomerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereKindCashId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereLastPaymentIntent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership wherePaymentIntents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership wherePendingOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereShippingStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read Customer|null $customer
 * @property-read \App\Models\KindCash|null $kindCash
 * @property bool|null $user_picked_gift
 * @property int|null $gift_product_id
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $giftProducts
 * @property-read int|null $gift_products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereGiftProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereUserPickedGift($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MembershipLog[] $logs
 * @property-read int|null $logs_count
 * @property int|null $product_id
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereProductId($value)
 * @property-read Product|null $product
 */
	class Membership extends \Eloquent {}
}

namespace App\Models{
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
	class MembershipLog extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductSubscription
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $status
 * @property string $uuid
 * @property int $customer_id
 * @property string $customer_email
 * @property string $total
 * @property int|null $payment_method_id
 * @property string $start_date
 * @property string|null $end_date
 * @property string|null $next_payment
 * @property string|null $last_payment
 * @property string|null $last_intent_date
 * @property mixed|null $billing
 * @property mixed|null $shipping
 * @property string $payment_interval
 * @property string|null $cause
 * @property string|null $shipping_method
 * @property int|null $payment_intents
 * @property int|null $active_order_id
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereActiveOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereBilling($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereCause($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereCustomerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereLastIntentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereLastPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereNextPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription wherePaymentIntents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription wherePaymentInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereShippingMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSubscription whereUuid($value)
 * @mixin \Eloquent
 */
	class ProductSubscription extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SubscriptionItem
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $product_subscription_id
 * @property int|null $variation_id
 * @property int|null $product_id
 * @property string $price
 * @property string|null $product_admin_slug
 * @property string|null $image
 * @property string|null $expiration_date
 * @property int $quantity
 * @property string $fee
 * @property string $total
 * @property mixed|null $interval_choices
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereExpirationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereIntervalChoices($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereProductAdminSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereProductSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionItem whereVariationId($value)
 * @mixin \Eloquent
 */
	class ProductSubscriptionItem extends \Eloquent {}
}

namespace App\Models\Subscription{
/**
 * App\Models\Subscription\SubscriptionProduct
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $expiration_date
 * @property int|null $price
 * @property int|null $fee
 * @property bool $use_parent_settings
 * @property object|null $intervals
 * @property-read \App\Models\WooCommerce\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct whereExpirationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct whereIntervals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionProduct whereUseParentSettings($value)
 */
	class SubscriptionProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Sync
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $description
 * @property int $user_id
 * @property string $status
 * @property array $info
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Sync newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sync newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sync query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereUserId($value)
 * @mixin \Eloquent
 * @property string $name
 * @property string $content_type
 * @property int|null $intents
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SyncLog[] $logs
 * @property-read int|null $logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SyncNote[] $notes
 * @property-read int|null $notes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereContentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereIntents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereName($value)
 * @property string|null $batch_id
 * @property int|null $current_page
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sync whereCurrentPage($value)
 * @property int|null $per_page
 * @method static \Illuminate\Database\Eloquent\Builder|Sync wherePerPage($value)
 */
	class Sync extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SyncLog
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $sync_id
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|SyncLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncLog whereSyncId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncLog whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Sync|null $sync
 */
	class SyncLog extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SyncNote
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $sync_id
 * @property int $user_id
 * @property string|null $note
 * @method static \Illuminate\Database\Eloquent\Builder|SyncNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncNote query()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncNote whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncNote whereSyncId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncNote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncNote whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Sync|null $sync
 * @property-read \App\Models\User|null $user
 */
	class SyncNote extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property int|null $current_connected_account_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConnectedAccount[] $connectedAccounts
 * @property-read int|null $connected_accounts_count
 * @property-read \App\Models\ConnectedAccount|null $currentConnectedAccount
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sync[] $syncs
 * @property-read int|null $syncs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentConnectedAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models\WooCommerce{
/**
 * App\Models\WooCommerce\Brand
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $woo_brand_id
 * @property string|null $name
 * @property string|null $slug
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand query()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereWooBrandId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\Product[] $products
 * @property-read int|null $products_count
 */
	class Brand extends \Eloquent {}
}

namespace App\Models\WooCommerce{
/**
 * App\Models\WooCommerce\Category
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $woo_category_id
 * @property string|null $name
 * @property string|null $slug
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereWooCategoryId($value)
 * @mixin \Eloquent
 * @property int|null $service_id
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereServiceId($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models\WooCommerce{
/**
 * App\Models\WooCommerce\Coupon
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $coupon_id
 * @property string $code
 * @property string $amount
 * @property \Illuminate\Support\Carbon $date_created
 * @property \Illuminate\Support\Carbon $date_modified
 * @property string $discount_type
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $date_expires
 * @property int $usage_count
 * @property bool $individual_use
 * @property mixed $settings
 * @property array|null $meta_data
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon query()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDateCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDateExpires($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDateModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereIndividualUse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereMetaData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereUsageCount($value)
 * @mixin \Eloquent
 * @property int|null $service_id
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereServiceId($value)
 * @property-read Service|null $service
 */
	class Coupon extends \Eloquent {}
}

namespace App\Models\WooCommerce{
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
	class Customer extends \Eloquent {}
}

namespace App\Models\WooCommerce{
/**
 * App\Models\WooCommerce\Order
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $parent_id
 * @property int $number
 * @property string $order_key
 * @property string $created_via
 * @property string $version
 * @property string $status
 * @property string $currency
 * @property \Illuminate\Support\Carbon $date_created
 * @property \Illuminate\Support\Carbon $date_modified
 * @property mixed $discount_total
 * @property mixed $discount_tax
 * @property mixed $shipping_total
 * @property mixed $shipping_tax
 * @property string $cart_tax
 * @property mixed $total
 * @property mixed $total_tax
 * @property bool $prices_include_tax
 * @property string|null $customer_ip_address
 * @property string|null $customer_user_agent
 * @property string|null $transaction_id
 * @property string|null $date_paid
 * @property string|null $date_completed
 * @property string|null $cart_hash
 * @property bool $set_paid
 * @property array|null $meta_data
 * @property \App\Casts\Address $billing
 * @property \App\Casts\Address $shipping
 * @property int $order_id
 * @property int|null $customer_id
 * @property-read \App\Models\WooCommerce\Customer|null $customer
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBilling($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCartHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCartTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedVia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDateCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDateCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDateModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDatePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscountTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscountTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereMetaData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePricesIncludeTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereSetPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotalTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereVersion($value)
 * @mixin \Eloquent
 * @property int|null $service_id
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereServiceId($value)
 * @property mixed|null $tax_lines
 * @property mixed|null $shipping_lines
 * @property mixed|null $coupon_lines
 * @property mixed|null $fee_lines
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\OrderLine[] $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCouponLines($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereFeeLines($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingLines($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTaxLines($value)
 * @property int|null $membership_id
 * @property bool $has_membership
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereHasMembership($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereMembershipId($value)
 * @property int|null $payment_id
 * @property-read \App\Models\WooCommerce\PaymentMethod|null $paymentMethod
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentId($value)
 * @property array|null $giftcards
 * @property string|null $giftcard_total
 * @property int|null $kindhuman_subscription_id
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereGiftcardTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereGiftcards($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereKindhumanSubscriptionId($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models\WooCommerce{
/**
 * App\Models\WooCommerce\OrderLine
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $order_line_id
 * @property string|null $name
 * @property int|null $product_id
 * @property int|null $variation_id
 * @property int $quantity
 * @property string|null $tax_class
 * @property mixed|null $subtotal
 * @property mixed|null $subtotal_tax
 * @property mixed|null $total
 * @property array|null $taxes
 * @property mixed|null $meta_data
 * @property string|null $sku
 * @property mixed|null $price
 * @property-read \App\Models\WooCommerce\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereMetaData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereOrderLineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereSubtotalTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereTaxClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereTaxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereVariationId($value)
 * @mixin \Eloquent
 * @property int|null $order_id
 * @property-read \App\Models\WooCommerce\Order|null $order
 * @method static \Illuminate\Database\Eloquent\Builder|OrderLine whereOrderId($value)
 */
	class OrderLine extends \Eloquent {}
}

namespace App\Models\WooCommerce{
/**
 * App\Models\WooCommerce\OrderNote
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $order_id
 * @property string|null $author
 * @property string $date_created
 * @property string|null $note
 * @property bool $customer_note
 * @property bool $added_by_user
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote whereAddedByUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote whereCustomerNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote whereDateCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class OrderNote extends \Eloquent {}
}

namespace App\Models\WooCommerce{
/**
 * App\Models\WooCommerce\PaymentMethod
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $payment_method_id
 * @property string $title
 * @property string|null $description
 * @property int $order
 * @property bool $enabled
 * @property string $method_title
 * @property string|null $method_description
 * @property mixed|null $method_supports
 * @property mixed|null $settings
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereMethodDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereMethodSupports($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereMethodTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\Order[] $orders
 * @property-read int|null $orders_count
 */
	class PaymentMethod extends \Eloquent {}
}

namespace App\Models\WooCommerce{
/**
 * App\Models\WooCommerce\Product
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $product_id
 * @property int|null $parent_id
 * @property string $name
 * @property string $slug
 * @property string $permalink
 * @property string $sku
 * @property \Illuminate\Support\Carbon|null $date_created
 * @property string|null $date_modified
 * @property string $type
 * @property string $status
 * @property bool $featured
 * @property bool $on_sale
 * @property bool $purchasable
 * @property bool $virtual
 * @property bool $manage_stock
 * @property int $stock_quantity
 * @property string $stock_status
 * @property bool $sold_individually
 * @property mixed $price
 * @property mixed $regular_price
 * @property mixed $sale_price
 * @property array $settings
 * @property array $meta_data
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDateCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDateModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereManageStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMetaData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereOnSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePermalink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePurchasable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereRegularPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSoldIndividually($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStockQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStockStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereVirtual($value)
 * @mixin \Eloquent
 * @property-read Product|null $children
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\ProductImage[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\ProductAttribute[] $attributes
 * @property-read int|null $attributes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\Tag[] $tags
 * @property-read int|null $tags_count
 * @property int|null $service_id
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereServiceId($value)
 * @property-read Service|null $service
 * @property-read \Illuminate\Database\Eloquent\Collection|Membership[] $memberships
 * @property-read int|null $memberships_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\Brand[] $brands
 * @property-read int|null $brands_count
 * @property-read Product|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\ProductAttribute[] $productAttributes
 * @property-read int|null $product_attributes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $variations
 * @property-read int|null $variations_count
 * @property bool|null $has_subscription
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Subscription\SubscriptionProduct|null $subscription
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereHasSubscription($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models\WooCommerce{
/**
 * App\Models\WooCommerce\ProductAttribute
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $attribute_id
 * @property string|null $name
 * @property int|null $position
 * @property bool|null $visible
 * @property bool|null $variation
 * @property mixed|null $options
 * @property int|null $product_id
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereVariation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereVisible($value)
 * @mixin \Eloquent
 */
	class ProductAttribute extends \Eloquent {}
}

namespace App\Models\WooCommerce{
/**
 * App\Models\WooCommerce\ProductImage
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $product_image_id
 * @property string|null $date_created
 * @property string|null $date_modified
 * @property string|null $src
 * @property string|null $name
 * @property string|null $alt
 * @property int $product_id
 * @property-read \App\Models\WooCommerce\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereDateCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereDateModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereProductImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereSrc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ProductImage extends \Eloquent {}
}

namespace App\Models\WooCommerce{
/**
 * App\Models\WooCommerce\ShippingMethod
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $shipping_method_id
 * @property string $title
 * @property int $order
 * @property bool $enabled
 * @property int|null $method_id
 * @property string|null $method_title
 * @property string|null $method_description
 * @property mixed|null $settings
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereMethodDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereMethodTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereShippingMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ShippingMethod extends \Eloquent {}
}

namespace App\Models\WooCommerce{
/**
 * App\Models\WooCommerce\ShippingZone
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $shipping_zone_id
 * @property string $title
 * @property int $order
 * @property mixed|null $locations
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereLocations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereShippingZoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ShippingZone extends \Eloquent {}
}

namespace App\Models\WooCommerce{
/**
 * App\Models\WooCommerce\Tag
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $woo_tag_id
 * @property string|null $name
 * @property string|null $slug
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereWooTagId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\Product[] $products
 * @property-read int|null $products_count
 * @property int|null $service_id
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereServiceId($value)
 */
	class Tag extends \Eloquent {}
}

namespace App\Models\WooCommerce{
/**
 * App\Models\WooCommerce\TaxRate
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $tax_rate_id
 * @property string $country
 * @property string|null $postcode
 * @property string|null $city
 * @property mixed|null $postcodes
 * @property mixed|null $cities
 * @property string|null $rate
 * @property string|null $name
 * @property int $priority
 * @property bool $compound
 * @property bool $shipping
 * @property string $class
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate query()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereCities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereCompound($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate wherePostcodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereTaxRateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $service_id
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereServiceId($value)
 */
	class TaxRate extends \Eloquent {}
}

namespace App\Models\WooCommerce{
/**
 * App\Models\WooCommerce\WooSetting
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $setting_id
 * @property string|null $label
 * @property string $description
 * @property int|null $parent_id
 * @property string|null $sub_groups
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting whereSettingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting whereSubGroups($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $service_id
 * @method static \Illuminate\Database\Eloquent\Builder|WooSetting whereServiceId($value)
 */
	class WooSetting extends \Eloquent {}
}

namespace App\Models\WooCommerce{
/**
 * App\Models\WooCommerce\WooSettingOption
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $setting_option_id
 * @property string $label
 * @property string|null $dscription
 * @property mixed $value
 * @property mixed $default
 * @property string $tip
 * @property string $placeholder
 * @property string $type
 * @property mixed $options
 * @property int $group_id
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereDscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption wherePlaceholder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereSettingOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereTip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WooSettingOption whereValue($value)
 * @mixin \Eloquent
 */
	class WooSettingOption extends \Eloquent {}
}

