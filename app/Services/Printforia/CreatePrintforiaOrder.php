<?php

namespace App\Services\Printforia;

use App\Models\Printforia\PrintforiaOrder;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Product;
use App\Services\BaseService;
use App\Services\Sync\Orders\UpdateData as UpdateOrderData;

class CreatePrintforiaOrder extends BaseService
{
    /**
     * Undocumented function
     *
     * @param [type] $content_type
     * @param [type] $element_id
     */
    public function __construct(public Order $order)
    {}

    /**
     * Handle the Service
     *
     * @return void
     */
    public function handle()
    {
        $printforiaOrderItems = [];

        foreach ($this->order->items as $item) {
            if (! $item->product->is_printforia) continue;
            $printforiaOrderItems[] = $this->getPrintforiaLineItem($item->product, $item->quantity);
        }

        if (count($printforiaOrderItems) > 0) {
            $response = (new PrintforiaApiClient)
                ->createOrder($this->order, $printforiaOrderItems);

            if ($response->ok()) {
                $printforiaOrder = PrintforiaOrder::firstOrNew([
                    'printforia_order_id' => $response->object()->id
                ]);

                ProcessPrintforiaOrder::dispatchWithoutValidations(
                    $this->order, $printforiaOrder
                );

                UpdateOrderData::dispatchWithoutValidations(
                    $this->order,
                    ['meta_data' => [
                        ['key' => '_printforia_order_id', 'value' => $response->object()->id]
                    ]]
                );
            }
        }
    }

    /**
     * Get Printforia Line Item
     *
     * @param Product $product
     * @param integer $quantity
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
            'prints' => $product->getPrintforiaPrints()
        ];
    }
}
