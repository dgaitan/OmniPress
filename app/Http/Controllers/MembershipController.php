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
        $status = '';
        $memberships = Membership::with(['customer', 'kindCash']);

        if ($request->input('perPage')) {
            $perPage = $request->input('perPage');
        }

        if ($request->input('status') && 'all' !== $request->input('status')) {
            $status = $request->input('status');
            $memberships->where('status', $status);
        }

        if ($request->input('s') && ! empty($request->input('s'))) {
            $s = $request->input('s');
            $memberships->where('customer_email', 'ilike', "%$s%");
        }

        // if ($request->input('s') && !empty($request->input('s'))) {
        //     $memberships = Membership::search($request->input('s'))->orderBy('date_created', 'desc');
        // } else {
        //     $orders = Order::with('customer')->orderBy('date_created', 'desc');
        // }
        
        $memberships = $memberships->orderBy('start_at', 'desc')->paginate($perPage);
        
        $data = $this->getPaginationResponse($memberships);
        $data = array_merge($data, [
            'memberships' => collect($memberships->items())->map(fn($m) => $m->toArray(false)),
            's' => $request->input('s') ?? '',
            'filterByStatus' => $request->input('filterByStatus') ?? '',
            'statuses' => Membership::getStatuses(),
            'status' => $status
        ]);

        return Inertia::render('Memberships/Index', $data);
    }
}
