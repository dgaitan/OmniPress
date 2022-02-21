<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class QueuesController extends Controller
{
    /**
     * QUeues Index
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $queues = Db::table('failed_jobs')->get();

        return Inertia::render('Queues/Index', [
            'queues' => $queues
        ]);
    }
}
