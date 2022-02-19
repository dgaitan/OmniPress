<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Sync;
use App\Jobs\WooCommerceSyncServiceJob;
use App\Http\Resources\SyncResource;
use App\Rules\SyncContentType;

class SyncController extends Controller
{
    public function index() {
        $syncs = Sync::with('user')->take(10)->orderBy('id', 'desc')->get();

        return Inertia::render('Sync/Index', [
            'syncs' => count($syncs) > 0 ? SyncResource::collection($syncs) : [],
            'sync' => count($syncs) > 0 ? new SyncResource($syncs->first()) : []
        ]);
    }

    /**
     * [execute description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function execute(Request $request) {
        $request->validateWithBag('syncForm', [
            'content_type' => ['required', new SyncContentType],
            'description' => ['max:500']
        ]);

        $sync = Sync::initialize(
            $request->content_type,
            $request->user(),
            $request->description
        );

        WooCommerceSyncServiceJob::dispatch(strtolower($sync->content_type));

        return redirect(route('kinja.sync.index'))
            ->with('message', 'Sync Initialized!');
    }

    /**
     * [check description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function check(Request $request) {
        $data = \App\Http\Clients\Client::checkConnection();

        return $data['environment'];
    }

    public function logs(Request $request, $id) {
        $sync = Sync::find($id);

        dd($sync->logs()->get()->toArray());
    }
}
