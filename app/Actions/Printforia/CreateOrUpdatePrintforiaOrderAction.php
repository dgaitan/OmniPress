<?php

namespace App\Actions\Printforia;

use App\Models\Printforia\PrintforiaOrder;
use App\Models\Printforia\PrintforiaOrderItem;
use App\Models\Printforia\PrintforiaOrderNote;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Product;
use App\Services\Printforia\PrintforiaApiClient;
use Lorisleiva\Actions\Concerns\AsAction;
use stdClass;

class CreateOrUpdatePrintforiaOrderAction
{
    use AsAction;

    public function handle(Order $order, string $printforiaOrderId)
    {
        $request = (new PrintforiaApiClient)->getOrder($printforiaOrderId);

        if (! $request->ok()) return;

        $printforiaOrder = PrintforiaOrder::firstOrNew([
            'printforia_order_id' => $printforiaOrderId
        ]);

        $printforiaApiData = $request->object();

        $printforiaOrder->fill([
            'printforia_order_id' => $printforiaApiData->id,
            'order_id' => $order->id, // It refers to the order id in kinja and not in kindhumans woocommerce
            'customer_reference' => $printforiaApiData->customer_reference,
            'ship_to_address' => $printforiaApiData->ship_to_address,
            'return_to_address' => $printforiaApiData->return_to_address,
            'shipping_method' => $printforiaApiData->shipping_method,
            'ioss_number' => $printforiaApiData->ioss_number,
            'status' => $printforiaApiData->status,
        ]);
        $printforiaOrder->save();

        $this->collectItems($printforiaApiData, $printforiaOrder);
        $this->collectNotes($printforiaApiData, $printforiaOrder);

        return $printforiaOrder;
    }

    /**
     * Collect printforia order items
     *
     * @return void
     */
    protected function collectItems(
        stdClass $printforiaApiData,
        PrintforiaOrder $printforiaOrder
    ) {
        collect($printforiaApiData->items)->map(function ($item) use ($printforiaOrder) {
            $orderItem = PrintforiaOrderItem::firstOrNew([
                'printforia_item_id' => $item->id,
            ]);

            $data = [
                'order_id' => $printforiaOrder->id,
                'customer_item_reference' => $item->customer_item_reference,
                'printforia_sku' => $item->sku,
                'quantity' => $item->quantity,
                'description' => $item->description,
                'prints' => $item->prints,
                'printforia_item_id' => $item->id,
            ];

            $productId = explode('-', $item->customer_item_reference)[1];
            $product = Product::whereProductId($productId)->first();

            if ($product) {
                $data['product_id'] = $product->id;
                $data['kindhumans_sku'] = $product->sku;
            }

            $orderItem->fill($data);
            $orderItem->save();
        });
    }

    /**
     * Process Order Notes
     *
     * @return void
     */
    protected function collectNotes(
        stdClass $printforiaApiData,
        PrintforiaOrder $printforiaOrder
    ) {
        if (! property_exists($printforiaApiData, 'order_notes')) return;

        collect($printforiaApiData->order_notes)->map(function ($note) use ($printforiaOrder) {
            $orderNote = PrintforiaOrderNote::firstOrNew([
                'title' => $note->title,
                'order_id' => $printforiaOrder->id,
            ]);

            $orderNote->fill([
                'order_id' => $printforiaOrder->id,
                'title' => $note->title,
                'body' => $note->body,
                'order_status_code' => $note->order_status_code,
                'note_date' => $note->note_date,
            ]);

            $orderNote->save();
        });
    }
}
