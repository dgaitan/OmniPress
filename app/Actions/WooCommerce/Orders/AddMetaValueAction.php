<?php

namespace App\Actions\WooCommerce\Orders;

use App\Models\WooCommerce\Order;
use App\Services\WooCommerce\DataObjects\Order as OrderDataObject;
use Lorisleiva\Actions\Concerns\AsAction;

class AddMetaValueAction
{
    use AsAction;

    public function handle(
        int|string|Order $orderId, string $key, mixed $value
    ): Order|OrderDataObject|null {
        return UpdateOrderAction::run(
            orderId: $orderId,
            params: ['meta_data' => [['key' => $key, 'value' => $value]]],
            sync: true
        );
    }
}
