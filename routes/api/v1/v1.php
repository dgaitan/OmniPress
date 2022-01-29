<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\MembershipController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\SyncController;

/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->name('kinja.api.v1.')->group(function () {

	Route::controller(MembershipController::class)->name('memberships.')->prefix('/memberships')->group(function () {
		Route::get('/', 'index')->name('index');
		Route::get('/{id}', 'show')->name('show');
		Route::post('/new', 'new')->name('create');
		Route::post('/{id}/cash/add', 'addCash')->name('add_cash');
		Route::get('/{email}/check', 'checkMembershipEmail')->name('check_email');
	});

	Route::controller(ProductController::class)->name('products.')->prefix('/products')->group(function () {
		Route::get('/', 'index')->name('index');
	});

	Route::controller(SyncController::class)->name('syncs.')->prefix('/sync')->group(function () {
		Route::get('/', 'index')->name('index');
		Route::post('/update', 'update')->name('update');
	});

});