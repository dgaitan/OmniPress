<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index(Request $request) {

        return Inertia::render('Services/Index', [
            'services' => $request->user()->currentTeam->organization->services(),
        ]);
    }

    public function create(Request $request) {
        return Inertia::render('Services/Create');
    }
}
