<?php

namespace App\Actions\Memberships;

use App\Jobs\SingleWooCommerceSync;
use App\Models\Membership;
use App\Services\WooCommerce\WooCommerceService;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;

class SetDefaultProductAction
{
    use AsAction;

    public function handle(Membership $membership): Membership
    {
        $order_id = $membership->orders()->first()->order_id;
        $api = WooCommerceService::make();
        $request = $api->memberships()->addGiftProduct($order_id);

        if ($request->failed()) {
            $membership->logs()->create([
                'description' => sprintf(
                    'There was an error trying to set the default gift product: %s',
                    $request->body()
                ),
            ]);

            return $membership;
        }

        $data = (object) $request->json();
        $membership->status = Membership::ACTIVE_STATUS;
        $membership->shipping_status = Membership::SHIPPING_PENDING_STATUS;
        $membership->save();

        $cacheKey = sprintf('woocommerce_order_%s', $order_id);
        Cache::tags('orders')->forget($cacheKey);

        if ($data->product_id) {
            $product = $api->products()->getAndSync((int) $data->product_id);
            $membership->giftProducts()->attach($product);
            $membership->gift_product_id = $product->product_id;
            $membership->save();

            SingleWooCommerceSync::dispatch(
                (int) $data->order_id, 'orders'
            );
        }

        return $membership;
    }
}
