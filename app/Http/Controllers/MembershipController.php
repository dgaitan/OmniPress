<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MembershipController extends Controller
{   
    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request) {
        $perPage = 50;

        if ($request->input('perPage')) {
            $perPage = $request->input('perPage');
        }

        // if ($request->input('s') && !empty($request->input('s'))) {
        //     $memberships = Membership::search($request->input('s'))->orderBy('date_created', 'desc');
        // } else {
        //     $orders = Order::with('customer')->orderBy('date_created', 'desc');
        // }
        
        $memberships = Membership::with(['customer', 'kindCash'])->orderBy('created_at', 'desc')->paginate($perPage);

        return Inertia::render('Memberships/Index', [
            'memberships' => collect($memberships->items())->map(fn($m) => $m->toArray(true)),
            'total' => $memberships->total(),
            'nextUrl' => $memberships->nextPageUrl(),
            'prevUrl' => $memberships->previousPageUrl(),
            'perPage' => $memberships->perPage(),
            'currentPage' => $memberships->currentPage(),
            's' => $request->input('s') ?? '',
            'filterByStatus' => $request->input('filterByStatus') ?? ''
        ]);
    }
}
