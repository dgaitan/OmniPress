<?php

namespace App\Http\Controllers;

use App\Http\Resources\CouponResource;
use App\Models\WooCommerce\Coupon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $coupons = Coupon::take(20)->orderBy('date_created', 'desc')->get();

        return Inertia::render('Coupons/Index', [
            'coupons' => CouponResource::collection($coupons),
        ]);
    }
}
