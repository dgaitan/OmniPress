<?php

use App\Http\Controllers\Api\V2\MembershipController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API V2 Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->name('kinja.api.v2.')->group(function () {
    /**
     * Api Endpoint
     * 
     * /api/v2/memberships/
     */
    Route::controller(MembershipController::class)
        ->name('memberships.')
        ->prefix('/memberships')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
            Route::get('/{id}/orders', 'membershipOrders')->name('membershipOrders');
            Route::get('/{id}/cash', 'membershipKindCash')->name('membershipKindCash');
        });
});
