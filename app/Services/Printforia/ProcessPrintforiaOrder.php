<?php

namespace App\Services\Printforia;

use App\Models\Printforia\PrintforiaOrder;
use App\Models\Printforia\PrintforiaOrderItem;
use App\Models\Printforia\PrintforiaOrderNote;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Product;
use App\Services\BaseService;
use stdClass;

class ProcessPrintforiaOrder extends BaseService
{
    /**
     * Undocumented function
     *
     * @param [type] $content_type
     * @param [type] $element_id
     */
    public function __construct(
        public Order $order,
        public PrintforiaOrder $printforiaOrder
    ) {}

    /**
     * Handle the Service
     *
     * @return void
     */
    public function handle()
    {
        $printforiaApiData = PrintforiaOrder::getOrderFromApi(
            $this->printforiaOrder->printforia_order_id
        );

        if (! $printforiaApiData->ok()) {
            return;
        }

        $printforiaApiData = $printforiaApiData->object();

        $this->printforiaOrder->fill([
            'printforia_order_id' => $printforiaApiData->id,
            'order_id' => $this->order->id, // It refers to the order id in kinja and not in kindhumans woocommerce
            'customer_reference' => $printforiaApiData->customer_reference,
            'ship_to_address' => $printforiaApiData->ship_to_address,
            'return_to_address' => $printforiaApiData->return_to_address,
            'shipping_method' => $printforiaApiData->shipping_method,
            'ioss_number' => $printforiaApiData->ioss_number,
            'status' => $printforiaApiData->status,
        ]);

        $this->printforiaOrder->save();
        $this->collectItems($printforiaApiData);
        $this->collectNotes($printforiaApiData);
    }

    /**
     * Collect printforia order items
     *
     * @return void
     */
    protected function collectItems(stdClass $printforiaApiData)
    {
        collect($printforiaApiData->items)->map(function ($item) {
            $orderItem = PrintforiaOrderItem::firstOrNew([
                'printforia_item_id' => $item->id,
            ]);

            $data = [
                'order_id' => $this->printforiaOrder->id,
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
    protected function collectNotes(stdClass $printforiaApiData)
    {
        collect($printforiaApiData->order_notes)->map(function ($note) {
            $orderNote = PrintforiaOrderNote::firstOrNew([
                'title' => $note->title,
                'order_id' => $this->printforiaOrder->id,
            ]);

            $orderNote->fill([
                'order_id' => $this->printforiaOrder->id,
                'title' => $note->title,
                'body' => $note->body,
                'order_status_code' => $note->order_status_code,
                'note_date' => $note->note_date,
            ]);

            $orderNote->save();
        });
    }
}
