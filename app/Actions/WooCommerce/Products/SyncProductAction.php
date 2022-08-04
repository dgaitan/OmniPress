<?php

namespace App\Actions\WooCommerce\Products;

use App\Models\WooCommerce\Product;
use App\Services\WooCommerce\DataObjects\Product as DataObjectsProduct;
use App\Services\WooCommerce\WooCommerceService;
use Lorisleiva\Actions\Concerns\AsAction;

class SyncProductAction
{
    use AsAction;

    public function handle(string|int|Product $productId): Product|DataObjectsProduct|null
    {
        $product = $productId instanceof Product
            ? $productId
            : Product::whereProductId($productId)->first();
        $api = WooCommerceService::make();

        if ($product->type === 'variation') {
            $productId = $product->parent->product_id;
        }

        return $api->products()->getAndSync($productId);
    }
}
