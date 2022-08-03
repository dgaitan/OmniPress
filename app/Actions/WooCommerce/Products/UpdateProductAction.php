<?php

namespace App\Actions\WooCommerce\Products;

use App\Models\WooCommerce\Product;
use App\Services\WooCommerce\DataObjects\Product as DataObjectsProduct;
use App\Services\WooCommerce\WooCommerceService;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateProductAction
{
    use AsAction;

    public function handle(
        int|string|Product $productId, array $params = [], bool $sync = false
    ): Product|DataObjectsProduct|null {
        if ($productId instanceof Product) {
            $productId = $productId->product_id;
        }

        $api = WooCommerceService::make();

        return $api->products()->update(
            element_id: $productId, params: $params, sync: $sync
        );
    }
}
