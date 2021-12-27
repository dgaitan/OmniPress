<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Organization
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $status
 * @property string $name
 * @property string|null $description
 * @property bool $is_default
 * @property int $owner_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $members
 * @property-read int|null $members_count
 * @property-read \App\Models\User $owner
 * @method static \Illuminate\Database\Eloquent\Builder|Organization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization query()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereUpdatedAt($value)
 */
	class Organization extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Service
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property int $creator_id
 * @property int $organization_id
 * @property string|null $description
 * @property string $type
 * @property string|null $access
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\Customer[] $customers
 * @property-read int|null $customers_count
 * @property-read \App\Models\Organization $organization
 * @method static \Illuminate\Database\Eloquent\Builder|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereUpdatedAt($value)
 */
	class Service extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Organization[] $my_organizations
 * @property-read int|null $my_organizations_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Organization[] $organizations
 * @property-read int|null $organizations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
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
 * @property \App\Data\Shared\AddressData $billing
 * @property \App\Data\Shared\AddressData $shipping
 * @property bool $is_paying_customer
 * @property string|null $avatar_url
 * @property \Spatie\LaravelData\DataCollection $meta_data
 * @property int|null $service_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WooCommerce\Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\Service|null $service
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
 * @property array $billing
 * @property array $shipping
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
 * @property string $date_created
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
 */
	class Product extends \Eloquent {}
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
 */
	class ShippingZone extends \Eloquent {}
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
 */
	class WooSettingOption extends \Eloquent {}
}

