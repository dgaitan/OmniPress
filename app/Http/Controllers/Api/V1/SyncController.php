<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\WooCommerce\Order;
use App\Services\Sync\BulkSincronization;
use App\Services\Sync\SyncronizeEntity;
use App\Services\WooCommerce\DataObjects\Order as OrderDataObject;
use Exception;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    public function index(Request $request)
    {
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
     * @param Request $request
     * @param int $id
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
}
