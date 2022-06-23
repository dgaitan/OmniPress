<?php
/**
 * Printforia services
 */

namespace App\Services\Printforia;

use App\Mail\Printforia\OrderShipped;
use App\Models\Printforia\PrintforiaOrder;
use App\Models\Printforia\PrintforiaOrderItem;
use App\Models\Printforia\PrintforiaOrderNote;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\OrderLine;
use App\Models\WooCommerce\Product;
use Doctrine\Common\Cache\Psr6\InvalidArgument;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;
use stdClass;

class PrintforiaService {

    /**
     * Get an order from api
     *
     * @param string $orderId
     * @return void
     */
    public static function getOrderFromApi(string $orderId) {
        return (new PrintforiaApiClient)->getOrder($orderId);
    }

    /**
     * Undocumented function
     *
     * @param string $orderId
     * @return PrintforiaOrder|null
     */
    public static function getFromOrderId(string $orderId): PrintforiaOrder|null {
        return PrintforiaOrder::wherePrintforiaOrderId($orderId)->first();
    }

    /**
     * Woo Order has a printforia order related?
     *
     * @param Order $order
     * @return boolean
     */
    public static function wooOrderHasPrintforia(Order $order): bool {
        return ! empty($order->getMetaValue('_printforia_order_id'));
    }

    /**
     * Update a printforia order
     *
     * @param PrintforiaOrder $printforiaOrder
     * @return void
     */
    public static function updatePrintforiaOrder(PrintforiaOrder $printforiaOrder)
    {
        $request = self::getOrderFromApi($printforiaOrder->printforia_order_id);

        if (! $request->ok()) return false;

        $printforiaOrder = self::updateOrder($request->object(), $printforiaOrder, $printforiaOrder->order_id);

        return PrintforiaOrder::find($printforiaOrder->id);
    }

    /**
     * Get Or Create a Printforia Order
     *
     * @param Order $order
     * @return void
     */
    public static function getOrCreatePrintforiaOrder(Order $order): PrintforiaOrder|bool
    {
        if (! self::wooOrderHasPrintforia($order)) return false;
        $order = Order::whereOrderId($order->order_id)->first(); // Is necessary to do this if order were updated

        $request = self::getOrderFromApi($order->getMetaValue('_printforia_order_id'));

        if (! $request->ok()) return false;

        $printforiaOrder = PrintforiaOrder::firstOrNew([
            'printforia_order_id' => $request->object()->id
        ]);

        $printforiaOrder = self::updateOrder($request->object(), $printforiaOrder, $order->id);

        return PrintforiaOrder::find($printforiaOrder->id);
    }

    /**
     * Update printforia order data
     *
     * @param stdClass $data
     * @param PrintforiaOrder $printforiaOrder
     * @param integer $order_id
     * @return PrintforiaOrder
     */
    public static function updateOrder(
        stdClass $data,
        PrintforiaOrder $printforiaOrder,
        int $order_id
    ): PrintforiaOrder {
        $printforiaOrder->fill([
            'printforia_order_id' => $data->id,
            'order_id' => $order_id,
            'customer_reference' => $data->customer_reference,
            'ship_to_address' => $data->ship_to_address,
            'return_to_address' => $data->return_to_address,
            'shipping_method' => $data->shipping_method,
            'ioss_number' => $data->ioss_number,
            'status' => $data->status,
        ]);
        $printforiaOrder->save();

        collect($data->items)->map(function ($item) use ($printforiaOrder) {
            $orderItem = PrintforiaOrderItem::firstOrNew([
                'printforia_item_id' => $item->id
            ]);
            $data = [
                'order_id' => $printforiaOrder->id,
                'customer_item_reference' => $item->customer_item_reference,
                'printforia_sku' => $item->sku,
                'quantity' => $item->quantity,
                'description' => $item->description,
                'prints' => $item->prints,
                'printforia_item_id' => $item->id
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

        collect($data->order_notes)->map(function ($note) use ($printforiaOrder) {
            $orderNote = PrintforiaOrderNote::firstOrNew([
                'title' => $note->title,
                'order_id' => $printforiaOrder->id
            ]);

            $orderNote->fill([
                'order_id' => $printforiaOrder->id,
                'title' => $note->title,
                'body' => $note->body,
                'order_status_code' => $note->order_status_code,
                'note_date' => $note->note_date
            ]);

            $orderNote->save();
        });

        return $printforiaOrder;
    }

    /**
     * Webhook actions
     *
     * @param Request $request
     * @throws Exception if status is not present in request
     * @return void
     */
    public static function webhookActions(Request $request) {
        if (! $request->has('status')) {
            throw new Exception('Status is not present in response');
        }

        if ($request->type === 'order_status_change') {
            if ($request->status === 'approved') {
                $printforiaOrder = self::getFromOrderId($request->order_id);
                $printforiaOrder->status = $request->status;
                $printforiaOrder->save();
            }

            if ($request->status === 'shipped') {
                $printforiaOrder = self::getFromOrderId($request->order_id);
                $printforiaOrder->status = $request->status;
                $printforiaOrder->carrier = $request->carrier;
                $printforiaOrder->tracking_number = $request->tracking_number;
                $printforiaOrder->tracking_url = $request->tracking_url;
                $printforiaOrder->save();

                Mail::to($printforiaOrder->ship_to_address->email)
                    ->queue(new OrderShipped($printforiaOrder));
            }
        }
    }

    /**
     * Validate Webbhook Signature
     *
     * @see https://developers.printforia.com/#webhooks
     *
     * To understand the structure of the signature. See the link pasted above.
     *
     * @param Request $request
     * @throws Exception if pritnforia token is not defined
     * @throws InvalidArgumentException if signature is not present in request.
     * @return bool
     */
    public static function validateWebhookSignature(Request $request): bool {
        $token = env('PRINTFORIA_API_KEY', false);

        if (! $token) {
            throw new Exception('Printforia Token is required to run webhooks');
        }

        if (! $request->hasHeader('X-Signature')) {
            throw new Exception('Siganture is not present in request.');
        }

        $xSignature = explode(';', $request->header('X-Signature'));
        $timestamp = explode('=', $xSignature[0])[1];
        $signature = explode('=', $xSignature[1])[1];
        $payload = json_encode($request->all(), JSON_UNESCAPED_SLASHES);
        $computedSignature = hash_hmac('sha256', "{$timestamp}.{$payload}", $token);

        if (! hash_equals($computedSignature, $signature)) {
            throw new Exception('Invalid Signature Request');
        }

        return true;
    }

    /**
     * Get Printforia Order Items has WooCOmmerce order items
     *
     * @param PrintforiaOrder $printforiaOrder
     * @return Collection
     */
    public static function getOrderItemsHasWooCommerceItems(PrintforiaOrder $printforiaOrder): Collection
    {
        $cacheKey = sprintf('printforia_items_%s', $printforiaOrder->id);

        if (Cache::tags('printforia')->has($cacheKey)) {
            return Cache::tags('printforia')->get($cacheKey);
        }

        return Cache::tags('printforia')->remember(
            $cacheKey, now()->addYear(), function () use ($printforiaOrder) {
                $productIds = PrintforiaOrderItem::whereOrderId($printforiaOrder->id)->pluck('product_id');

                return OrderLine::with('product')
                    ->whereOrderId($printforiaOrder->order_id)
                    ->where('product_id', $productIds)
                    ->get();
            }
        );
    }
}
