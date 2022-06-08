<?php

namespace App\Models\WooCommerce;

use App\Models\Membership;
use App\Models\Concerns\HasMetaData;
use App\Models\Subscription\SubscriptionProduct;
use App\Models\Subscription\Subscriptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Scout\Searchable;
use Illuminate\Notifications\Notifiable;

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
 * @property-read SubscriptionProduct|null $subscription
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereHasSubscription($value)
 */
class Product extends Model
{
    use HasFactory;
    use HasMetaData;
    use Subscriptable;
    use Searchable;
    use Notifiable;

    protected $casts = [
        'price' => 'decimal:2',
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'settings' => 'object',
        'meta_data' => 'array',
        'date_created' => 'datetime'
    ];

    protected $fillable = [
        'product_id',
        'parent_id',
        'name',
        'slug',
        'permalink',
        'sku',
        'date_created',
        'date_modified',
        'type',
        'status',
        'featured',
        'on_sale',
        'purchasable',
        'virtual',
        'manage_stock',
        'stock_quantity',
        'stock_status',
        'sold_individually',
        'price',
        'regular_price',
        'sale_price',
        'settings',
        'meta_data',
        'is_subscription'
    ];

    /**
     * Child Products
     */
    public function variations(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Parent PRoduct
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id')->with('images');
    }

    /**
     * Images
     *
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    /**
     * [categories description]
     * @return [type] [description]
     */
    public function categories() {
        return $this->belongsToMany(
            Category::class,
            'product_category',
            'product_id',
            'category_id'
        )->as('categories')->withTimestamps();
    }

    /**
     * [tags description]
     * @return [type] [description]
     */
    public function tags() {
        return $this->belongsToMany(
            Tag::class,
            'product_tag',
            'product_id',
            'tag_id'
        )->as('tags')->withTimestamps();
    }

    /**
     * [memberships description]
     * @return [type] [description]
     */
    public function memberships() {
        return $this->belongsToMany(
            Membership::class,
            'membership_product',
            'product_id',
            'membership_id'
        )->as('memberships')->withTimestamps();
    }

    public function attributes() {
        return $this->belongsToMany(
            ProductAttribute::class,
            'product_attribute',
            'product_id',
            'product_attribute_id'
        )->as('product_attributes')->withTimestamps();
    }

    /**
     * Product Attributes
     *
     * @return
     */
    public function productAttributes() {
        return $this->belongsToMany(
            ProductAttribute::class,
            'product_attribute',
            'product_id',
            'product_attribute_id'
        )->as('product_attributes')->withTimestamps();
    }

    /**
     * Product Brands
     *
     * @return
     */
    public function brands() {
        return $this->belongsToMany(
            Brand::class,
            'product_brand',
            'product_id',
            'product_brand_id'
        )->as('product_brands')->withTimestamps();
    }

    /**
     * IS the current producdt a variation?
     *
     * @return boolean
     */
    public function isVariation() {
        return $this->type === 'variation';
    }

    /**
     * Subscription Product if it has one
     *
     * @return HasOne
     */
    public function subscription(): HasOne
    {
        return $this->hasOne(SubscriptionProduct::class, 'product_id');
    }

    /**
     * Get Permalink on this app
     *
     * @return string
     */
    public function getKinjaPermalink(): string {
        return route('kinja.products.show', [$this->product_id]);
    }

    /**
     * Get Permalink on client store
     *
     * @return string
     */
    public function getStorePermalink(): string {
        return sprintf(
            '%s/wp-admin/post.php?post=%s&action=edit',
            env('CLIENT_DOMAIN', 'https://kindhumans.com'),
            $this->product_id
        );
    }

    /**
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return sprintf('%s_woocommerce_products_index', env('MEILISEARCH_PREFIX', 'dev'));
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'slug' => $this->slug,
            'name' => $this->name,
            'permalink' => $this->permalink,
            'price' => $this->price,
            'date_created' => $this->date_created,
            'type' => $this->type,
            'status' => $this->status,
            'regular_price' => $this->regular_price,
            'sku' => $this->sku,
            'stock_status' => $this->stock_status,
            'categories' => $this->categories()->pluck('name')->toArray(),
            'tags' => $this->tags()->pluck('name')->toArray(),
            'brands' => $this->tags()->pluck('name')->toArray()
        ];
    }

    /**
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeSearchable()
    {
        return ! $this->isVariation();
    }

    /**
     * Modify the query used to retrieve models when making all of the models searchable.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function makeAllSearchableUsing($query) {
        return $query->with(['categories', 'tags', 'brands']);
    }

    public function toArray(array $args = []) {
        $array = parent::toArray();
        $args = array_replace([
            'withImages' => false
        ], $args);

        $array['categories'] = collect($this->categories)->map(function ($cat) {
            return $cat->toArray();
        });

        if (isset($args['withTags']) && $args['withTags']) {
            $array['tags'] = collect($this->tags)->map(function ($tag) {
                return $tag->toArray();
            });
        }

        $array['date_created'] = $this->date_created
            ? $this->date_created->format('F j, Y')
            : '';

        if ($args['withImages']) {
            $array['images'] = collect($this->images)->map(function ($image) {
                return $image->toArray();
            });
        }

        return $array;
    }

    public static function searchByKey(string $q) {
        $q = explode(':', $q);

        if (count($q) === 1) {
            return self::search(end($q));
        }

        $key = $q[0];
        $query = trim($q[1]);
        $searchBy = [
            'product_id', 'name', 'slug',
            'sku', 'type', 'status'
        ];

        $products = self::with(['categories', 'images', 'tags'])
            ->orderBy('date_created', 'desc');

        if (in_array($key, $searchBy)) {
            return $products->where($key, 'LIKE', "$query%");
        }

        return $products;
    }

    public function routeNotificationForSlack($notification)
    {
        return env('SLACK_CHANNEL_URL', 'https://hooks.slack.com/services/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');
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
        return env('NOTIFICATION_EMAILS', 'martijn@kindhumans.com');

        // Return email address and name...
        // return [$this->email_address => $this->name];
    }
}
