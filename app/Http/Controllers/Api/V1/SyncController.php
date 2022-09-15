<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\WooCommerce\Products\SyncProductAction;
use App\Actions\WooCommerce\SyncResourceAction;
use App\Http\Controllers\Controller;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Product;
use App\Services\Sync\BulkSincronization;
use App\Services\Sync\SyncronizeEntity;
use App\Services\WooCommerce\DataObjects\Order as OrderDataObject;
use App\Services\WooCommerce\DataObjects\Product as ProductDataObject;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SyncController extends Controller
{
    public function index(Request $request)
    {
    }

    /**
     * Sync A Resource
     *
     * @param  Request  $request
     * @return void
     */
    public function syncResource(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'resource' => 'required|string',
            'data' => 'required|array',
        ]);

        Log::info(
            sprintf(
                'Starting Sync of %s #%s',
                $request->resource,
                $request->data['id']
            )
        );

        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors(),
            ], 400);
        }

        SyncResourceAction::dispatch(
            resourceName: $request->resource,
            data: $request->data
        );

        /**
         * If user is cancelling an order. Let's be sure
         * of cancell the printforia order as well.
         */
        if (
            $request->resource === 'orders'
            && $request->data['status'] === 'cancelled'
        ) {
            $order = Order::whereOrderId($request->data['id'])->first();

            if (! is_null($order) && $order->printforiaOrder()->exists()) {
                $order->printforiaOrder->cancelOrder();
            }
        }

        if (
            $request->resource === 'products'
            && ( $request->data['status'] === 'publish' || $request->data['type'] === 'variable' )
        ) {
            SyncProductAction::dispatch(productId: $request->data['id']);
        }

        return response()->json([
            'message' => 'Resource has been updated successfully!',
        ], 200);
    }

    /**
     * [update description]
     *
     * @param  Request  $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
        try {
            SyncronizeEntity::dispatch($request->content_type, $request->element_id);

            return response()->json(['status' => 'Syncing started']);
        } catch (Exception $e) {
            return response()->json([
                'errors' => json_decode($e->getMessage()),
            ], 400);
        }
    }

    /**
     * Bulk Sync
     *
     * @param  Request  $request
     * @return void
     */
    public function bulkSync(Request $request)
    {
        try {
            BulkSincronization::dispatch($request->content_type, $request->ids);

            return response()->json(['status' => 'Syncing started']);
        } catch (Exception $e) {
            return response()->json([
                'errors' => json_decode($e->getMessage()),
            ], 400);
        }
    }

    /**
     * Endpoint to update an order
     *
     * @param  Request  $request
     * @param  int  $id
     * @return void
     */
    public function updateOrder(Request $request, $id)
    {
        $order = Order::whereOrderId($id)->first();

        if (is_null($order)) {
            return response()->json(['status' => 'Order does not exists'], 404);
        }

        $dataObject = new OrderDataObject((array) $request->input('data'));
        $dataObject->sync();

        return response()->json([
            'status' => 'Order Updated and Synced',
        ]);
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::whereProductId($id)->first();

        if (is_null($product)) {
            return response()->json(['status' => 'Product does not exists'], 404);
        }

        $dataObject = new ProductDataObject((array) $request->input('data'));
        $dataObject->sync();

        return response()->json([
            'status' => 'Product Updated and Synced',
        ]);
    }
}
