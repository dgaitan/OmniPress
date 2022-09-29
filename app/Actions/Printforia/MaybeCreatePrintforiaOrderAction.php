<?php

namespace App\Actions\Printforia;

use App\Actions\WooCommerce\Orders\UpdateOrderAction;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Product;
use App\Services\Printforia\PrintforiaApiClient;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;

class MaybeCreatePrintforiaOrderAction
{
    use AsAction;

    /**
     * Create printforia order action
     *
     * @param Order $order
     * @return bool|object
     */
    public function handle(Order $order): bool|object
    {
        $order = Order::find($order->id);
        $printforiaOrderItems = [];

        foreach ($order->items as $item) {
            if (! $item->product->is_printforia) {
                continue;
            }

            $printforiaOrderItems[] = $this->getPrintforiaLineItem(
                $item->product, 
                $item->quantity
            );
        }

        if (count($printforiaOrderItems) === 0) {
            return false;
        }

        $response = (new PrintforiaApiClient)
            ->createOrder($order, $printforiaOrderItems);

        if (! $response->ok()) {
            Log::error(
                sprintf(
                    'Error creating Printforia Order: %s',
                    $response->body()
                )
            );

            return false;
        }

        CreateOrUpdatePrintforiaOrderAction::run($order, $response->object()->id);
        UpdateOrderAction::run(
            $order->order_id,
            ['meta_data' => [
                ['key' => '_printforia_order_id', 'value' => $response->object()->id],
            ]],
            true
        );

        return $response->object();
    }

    /**
     * Get Printforia Line Item
     *
     * @param  Product  $product
     * @param  int  $quantity
     * @return array
     */
    public function getPrintforiaLineItem(Product $product, int $quantity): array
    {
        return [
            'customer_item_reference' => sprintf(
                'product-%s-%s', $product->product_id, $product->sku
            ),
            'quantity' => $quantity,
            'sku' => $product->getPrintforiaSku(),
            'description' => $product->getPrintforiaDescription(),
            'prints' => $product->getPrintforiaPrints(),
        ];
    }
}
