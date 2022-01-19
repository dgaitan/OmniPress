<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Sync;
use App\Tasks\WooCommerceTask;
use Carbon\Carbon;
use App\Http\Resources\SyncResource;

class SyncController extends Controller
{
    public function index() {
        return Inertia::render('Sync/Index', [
            'syncs' => SyncResource::collection(Sync::take(10)->orderBy('id', 'desc')->get())
        ]);
    }

    /**
     * [execute description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function execute(Request $request) {
        $request->validateWithBag('syncForm', [
            'content_type' => ['required'],
            'description' => ['max:500']
        ]);

        $contentTypes = ['customers', 'coupons', 'orders'];
        if (!in_array($request->content_type, $contentTypes)) {
            return redirect(route('kinja.sync.index'))->with('message', 'Invalid Content Type');
        }

        $sync = Sync::create([
            'name' => sprintf('%s sync', ucwords($request->content_type)),
            'status' => Sync::PENDING,
            'content_type' => $request->content_type,
            'user_id' => $request->user()->id,
            'description' => $request->description,
            'intents' => 1
        ]);

        $firstSyncLog = sprintf(
            'Sync started by %s at %s',
            $request->user()->name,
            Carbon::now()->format('F j, Y @ H:i:s')
        );
        $sync->logs()->create(['description' => $firstSyncLog]);


        $task = new WooCommerceTask;
        $task->_sync($request->content_type);

        $sync->status = Sync::COMPLETED;
        $sync->save();

        $completedLog = sprintf(
            'Sync completed successfuly at %s', Carbon::now()->format('F j, Y @ H:i:s')
        );
        $sync->logs()->create(['description' => $completedLog]);

        return redirect(route('kinja.sync.index'))
            ->with('message', 'Sync Completed!');
    }
}
