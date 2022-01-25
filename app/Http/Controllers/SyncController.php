<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Models\Sync;
use App\Tasks\WooCommerceTask;
use App\Http\Resources\SyncResource;
use App\Rules\SyncContentType;
use App\Jobs\WooCommerceSyncJob;

class SyncController extends Controller
{
    public function index() {
        $syncs = Sync::with('user')->take(10)->orderBy('id', 'desc')->get();
        
        return Inertia::render('Sync/Index', [
            'syncs' => SyncResource::collection($syncs),
            'sync' => new SyncResource($syncs->first())
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

        WooCommerceSyncJob::dispatch($sync->id);

        return redirect(route('kinja.sync.index'))
            ->with('message', 'Sync Initialized!');
    }

    public function intent(Request $request) {
        $request->validateWithBag('syncIntentError', [
            'sync_id' => ['required']
        ]);

        $sync = Sync::findOrFail($request->sync_id);

        if ($sync->user_id !== $request->user()->id) {
            throw new Exception("Invalid Request. You are not the owner");
        }

        $sync->add_log(sprintf(
            'Running another sync intent at %s',
            Carbon::now()->format('F j, Y @ H:i:s')
        ));

        $sync->execute();

        return redirect(route('kinja.sync.index'));

        try {

        } catch (Exception $e) {
            return redirect(route('kinja.sync.index'))
                ->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function resume(Request $request, $id) {
        return dd(Bus::findBatch($id));
    }
}
