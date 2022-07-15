<?php

namespace App\Actions\WooCommerce\Orders;

use App\Models\WooCommerce\Order;
use App\Services\WooCommerce\DataObjects\Order as OrderDataObject;
use App\Services\WooCommerce\WooCommerceService;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateOrderAction
{
    use AsAction;

    public function handle(
        int|string|Order $orderId, array $params = [], bool $sync = false
    ): Order|OrderDataObject|null {
        if ($orderId instanceof Order) {
            $orderId = $orderId->order_id;
        }

        $api = WooCommerceService::make();

        return $api->orders()->update(
            element_id: $orderId, params: $params, sync: $sync
        );
    }
}
