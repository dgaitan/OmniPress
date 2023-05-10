<?php

use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\MembershipController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\PreOrderController;
use App\Http\Controllers\Api\V1\PrintforiaController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\SyncController;
use Illuminate\Support\Facades\Route;

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

    // Quick Api Connection Check
    Route::get('/check', function () {
        return response()->json([
            'domain' => env('APP_URL'),
            'app_name' => env('APP_NAME'),
            'status' => 'ok',
        ], 200);
    });

    Route::post('/check-post', function () {
        return response()->json([
            'domain' => env('APP_URL'),
            'app_name' => env('APP_NAME'),
            'status' => 'ok',
        ], 200);
    });

    Route::controller(MembershipController::class)->name('memberships.')->prefix('/memberships')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::post('/new', 'new')->name('create');
        Route::post('/renew', 'renew')->name('renew');
        Route::post('/pick-gift', 'pickGift')->name('pickGift');
        Route::post('/cancell/{id}', 'cancell')->name('cancell');
        Route::post('/{id}/cash/add', 'addCash')->name('add_cash');
        Route::post('/{id}/cash/redeem', 'redeemCash')->name('redeem_cash');
        Route::get('/{email}/check', 'checkMembershipEmail')->name('check_email');
    });

    Route::controller(ProductController::class)->name('products.')->prefix('/products')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::controller(SyncController::class)->name('syncs.')->prefix('/sync')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/sync-resource', 'syncResource')->name('syncResource');
        Route::post('/update', 'update')->name('update');
        Route::post('/bulk-sync', 'bulkSync')->name('bulkSync');
        Route::post('/orders/{id}/update', 'updateOrder')->name('updateOrder');
        Route::post('/products/{id}/update', 'updateProduct')->name('updateProduct');
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

    Route::controller(PrintforiaController::class)->name('printforia.')->prefix('/printforia')->group(function () {
        Route::get('/orders/{orderId}', 'getOrder')->name('order.show');
        Route::post('/webhook-values/{orderId}', 'getWebhookValues')->name('webhook-values');
    });

    Route::controller(CustomerController::class)->name('customers.')
        ->prefix('/customers')
        ->group(function () {
            Route::post('/profile', 'profile')->name('profile');
        });

    Route::controller(PreOrderController::class)->name('preOrders.')
        ->prefix('/pre-orders')
        ->group(function () {
            Route::get('/index', 'index')->name('index');
            Route::post('/create', 'create')->name('create');
            Route::get('/{id}', 'show')->name('show');
            Route::put('/{id}/update', 'update')->name('update');
        });
});
