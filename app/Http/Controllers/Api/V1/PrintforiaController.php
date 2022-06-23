<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Printforia\PrintforiaOrder;
use App\Http\Controllers\Controller;
use App\Http\Resources\Printforia\PrintforiaResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PrintforiaController extends Controller
{
    /**
     * Get Printforia Order Detail
     *
     * @param Request $request
     * @param [type] $orderId
     * @return void
     */
    public function getOrder(Request $request, $orderId) {
        $order = PrintforiaOrder::wherePrintforiaOrderId($orderId)->first();

        if (is_null($order)) {
            return response()->json([
                'message' => 'Order Not Found!'
            ], 404);
        }

        return response()->json(new PrintforiaResource($order));
    }
}
