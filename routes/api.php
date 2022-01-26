<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/products', function (Request $request) {
    if ($request->input('s')) {
        $products = \App\Models\WooCommerce\Product::search($request->input('s'))->take(10);
    } else {
        $products = \App\Models\WooCommerce\Product::take(10);
    }
    
    return $products->orderBy('date_created', 'desc')->get();
});