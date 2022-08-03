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
        int|string|Product $product, array $params = [], bool $sync = false
    ): Product|DataObjectsProduct|null {
        if (! $product instanceof Product) {
            $product = Product::whereProductId($product)->first();
        }

        $api = WooCommerceService::make();

        if ($product->type === 'variation') {
            $response = $api->put(
                endpoint: sprintf('products/%s/variations/%s', $product->parent->product_id, $product->product_id),
                data: $params
            );

            return $response->ok()
                ? Product::whereProductId($response->object()->id)->first()
                : null;
        }

        return $api->products()->update(
            element_id: $product->product_id, params: $params, sync: $sync
        );
    }
}
