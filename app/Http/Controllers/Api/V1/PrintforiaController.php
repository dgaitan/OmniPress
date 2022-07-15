<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Printforia\PrintforiaResource;
use App\Models\Printforia\PrintforiaOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PrintforiaController extends Controller
{
    /**
     * Get Printforia Order Detail
     *
     * @param  Request  $request
     * @param [type] $orderId
     * @return void
     */
    public function getOrder(Request $request, $orderId)
    {
        $order = PrintforiaOrder::wherePrintforiaOrderId($orderId)->first();

        if (is_null($order)) {
            return response()->json([
                'message' => 'Order Not Found!',
            ], 404);
        }

        return response()->json(new PrintforiaResource($order));
    }

    public function getWebhookValues(Request $request, $orderId)
    {
        if (env('APP_ENV', 'local') === 'production') {
            return response()->json([
                'message' => 'Invalid request'
            ], 403);
        }

        $order = PrintforiaOrder::wherePrintforiaOrderId($orderId)->first();

        if (is_null($order)) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        if (! in_array($request->get('status'), ['shipped', 'approved'])) {
            return response()->json([
                'message' => 'Invalid status. Please use shipped or approved'
            ], 200);
        }

        if ($request->get('status') === 'shipped') {
            $trackingNumber = Str::random(20);
            $data = [
                'status' => $request->get('status'),
                'type' => 'order_status_change',
                'order_id' => $orderId,
                'customer_reference' => $order->customer_reference,
                'carrier' => 'USPS',
                'tracking_number' => $trackingNumber,
                'tracking_url' => sprintf('https://tracking.example.com/%s', $trackingNumber)
            ];
        }

        if ($request->get('status') === 'approved') {
            $data = [
                'type' => 'order_status_change',
                'status' => 'approved',
                'order_id' => $orderId,
                'customer_reference' => $order->customer_reference
            ];
        }

        $payload = json_encode($data, JSON_UNESCAPED_SLASHES);
        $signature = hash_hmac('sha256', "1656985209.{$payload}", env('PRINTFORIA_API_KEY', 'foo'));
        $signature = sprintf('t=1656985209;s=%s', $signature);

        return response()->json([
            'signature' => $signature,
            'data' => $data
        ]);
    }
}
