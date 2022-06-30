<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Jobs\SingleWooCommerceSync;
use App\Rules\SyncContentType;
use App\Services\Sync\BulkSincronization;
use App\Services\Sync\SyncronizeEntity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Validator;

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
            SyncronizeEntity::dispatch([
                'content_type' => $request->content_type,
                'element_id' => $request->element_id
            ]);

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
            BulkSincronization::dispatch([
                'content_type' => $request->content_type,
                'ids' => $request->ids
            ]);

            return response()->json(['status' => 'Syncing started']);

        } catch (Exception $e) {
            return response()->json([
                'errors' => json_decode($e->getMessage()),
            ], 400);
        }
    }
}
