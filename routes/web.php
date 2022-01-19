<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use JoelButcher\Socialstream\Http\Controllers\OAuthController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CouponController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::get(
    '/oauth/{provider}/callback', 
    [OAuthController::class, 'handleProviderCallback']
)->middleware('kindhumans.email')->name('oauth.callback');


Route::middleware(['auth:sanctum', 'verified'])->prefix('/dashboard')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Sync Routes
    Route::get('/sync', [SyncController::class, 'index'])->name('kinja.sync.index');
    Route::post('/sync/execute', [SyncController::class, 'execute'])->name('kinja.sync.execute');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('kinja.customers.index');

    // Coupons
    Route::get('/coupons', [CouponController::class, 'index'])->name('kinja.coupons.index');
});
