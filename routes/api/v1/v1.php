<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\MembershipController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\SyncController;
use App\Http\Controllers\Api\V1\PaymentController;

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
		Route::post('/{id}/cash/redeem', 'redeemCash')->name('redeem_cash');
		Route::get('/{email}/check', 'checkMembershipEmail')->name('check_email');
	});

	Route::controller(ProductController::class)->name('products.')->prefix('/products')->group(function () {
		Route::get('/', 'index')->name('index');
	});

	Route::controller(SyncController::class)->name('syncs.')->prefix('/sync')->group(function () {
		Route::get('/', 'index')->name('index');
		Route::post('/update', 'update')->name('update');
	});

	Route::controller(PaymentController::class)->name('payments.')->prefix('/payments')->group(function () {
		Route::post('/intent', 'intent')->name('intent');
		Route::post('/direct', 'direct')->name('direct');
		Route::get('/{customer_id}/payment-methods', 'paymentMethods')->name('paymentMethods');
		Route::post('/add', 'addPaymentMethod')->name('add');
		Route::post('/delete', 'deletePaymentMethod')->name('delete');
		Route::post('/set-default', 'setDefaultPaymentMethod')->name('setDefault');
		Route::get('/{customer_id}/default-payment-method', 'getDefaultPaymentMethod')->name('defaultPaymentMethod');
	});

});