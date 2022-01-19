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

    public function execute(Request $request) {
        $info = [
            sprintf('Sync started at %s', Carbon::now()->format('F j, Y'))
        ];
        $sync = Sync::create([
            'status' => Sync::PENDING,
            'user_id' => $request->user()->id,
            'info' => json_encode($info)
        ]);

        $task = new WooCommerceTask;
        $task->syncCustomers();

        $sync->status = Sync::COMPLETED;
        $sync->save();

        return redirect(route('kinja.sync.index'))
            ->with('message', 'Sync Completed!');
    }
}
