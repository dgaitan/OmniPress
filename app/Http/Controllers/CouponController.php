<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WooCommerce\Coupon;
use App\Http\Resources\CouponResource;
use Inertia\Inertia;

class CouponController extends Controller
{
    public function index(Request $request) {
        $coupons = Coupon::take(20)->orderBy('date_created', 'desc')->get();

        return Inertia::render('Coupons/Index', [
            'coupons' => CouponResource::collection($coupons)
        ]);
    }
}
