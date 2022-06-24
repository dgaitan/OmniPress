<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Rules\SyncContentType;
use App\Jobs\SingleWooCommerceSync;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Validator;

class SyncController extends Controller
{
    public function index(Request $request) {}

    /**
     * [update description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request) {
        // Validate the params
        $validator = Validator::make($request->all(), [
            'content_type' => ['required', new SyncContentType],
            'element_id' => ['required', 'integer']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        SingleWooCommerceSync::dispatch($request->element_id, $request->content_type);

        return response()->json(['status' => 'Syncing started']);
    }

    /**
     * Bulk Sync
     *
     * @param Request $request
     * @return void
     */
    public function bulkSync(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content_type' => ['required', new SyncContentType],
            'ids' => ['required', 'array']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $tasks = collect($request->ids)->map(function ($id) use ($request) {
            return new SingleWooCommerceSync($id, $request->content_type);
        });

        Bus::chain($tasks)->dispatch();
    }
}
