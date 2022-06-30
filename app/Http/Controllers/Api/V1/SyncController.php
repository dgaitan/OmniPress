<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Sync\BulkSincronization;
use App\Services\Sync\SyncronizeEntity;
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
}
