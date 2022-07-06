<?php

namespace App\Actions\Printforia;

use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Product;
use App\Services\Printforia\PrintforiaApiClient;
use Lorisleiva\Actions\Concerns\AsAction;

class MaybeCreatePrintforiaOrderAction
{
    use AsAction;

    public function handle(Order $order)
    {
        $printforiaOrderItems = [];

        foreach ($order->items as $item) {
            if (! $item->product->is_printforia) {
                continue;
            }

            $printforiaOrderItems[] = $this->getPrintforiaLineItem($item->product, $item->quantity);
        }

        if (count($printforiaOrderItems) === 0) return;

        $response = (new PrintforiaApiClient)
            ->createOrder($order, $printforiaOrderItems);

        if ($response->ok()) {
            CreateOrUpdatePrintforiaOrderAction::run($response->object()->id);
        }
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
            'description'=> $product->getPrintforiaDescription(),
            'prints' => $product->getPrintforiaPrints(),
        ];
    }
}
