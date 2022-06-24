<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CauseController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QueuesController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebhookController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use JoelButcher\Socialstream\Http\Controllers\OAuthController;

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
    if (Auth::check()) {
        return to_route('dashboard');
    }

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
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');

    // Admin Views
    Route::prefix('/admin')
        ->middleware(['role:super_admin|admin'])
        ->group(function () {
            // Sync Routes
            Route::controller(SyncController::class)
            ->middleware(['can:run_sync'])->name('kinja.sync.')->prefix('/sync')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/execute', 'execute')->name('execute');
                Route::get('/logs/{id}', 'logs')->name('logs');
                Route::get('/check', 'check')->name('check');
            });

            // Queues
            Route::controller(QueuesController::class)
            ->middleware(['can:admin_queues'])->name('kinja.queues.')->prefix('/queues')->group(function () {
                Route::get('/', 'index')->name('index');
            });

            Route::controller(UserController::class)
            ->middleware('can:assign_roles')->name('kinja.users.')->prefix('/users')->group(function () {
                Route::get('/', 'index')->name('index');
            });
        });

    // Store Views
    Route::prefix('/store')->group(function () {
        // Customers
        Route::controller(CustomerController::class)->prefix('/customers')->group(function () {
            Route::get('/', 'index')->name('kinja.customers.index');
            Route::get('/{id}', 'show')->name('kinja.customers.show');
        });

        // Coupons
        Route::controller(CouponController::class)->prefix('/coupons')->group(function () {
            Route::get('/coupons', 'index')->name('kinja.coupons.index');
        });

        // Products
        Route::controller(ProductController::class)->prefix('/products')->group(function () {
            Route::get('/', 'index')->name('kinja.products.index');
            Route::get('/export-subscriptions', 'exportSubscriptions')
                ->name('kinja.products.export-subscriptions');
            Route::get('/{id}', 'show')->name('kinja.products.show');
        });

        // Orders
        Route::controller(OrderController::class)->prefix('/orders')->group(function () {
            Route::get('/', 'index')->name('kinja.orders.index');
            Route::get('/export', 'export')->name('kinja.orders.export');
            Route::get('/printforia', 'printforiaOrders')->name('kinja.orders.printforiaOrders');
            Route::get('/printforia/{id}', 'printforiaDetail')->name('kinja.orders.printforiaDetail');
            Route::get('/{id}', 'show')->name('kinja.orders.show');
        });

        Route::controller(AnalyticsController::class)->prefix('/analytics')->group(function () {
            Route::get('/', 'index')->name('kinja.analytics.index');
        });
    });

    Route::prefix('/kindhumans')->name('kinja.')->group(function () {
        // Memberships
        Route::controller(MembershipController::class)->prefix('/memberships')->name('memberships.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/actions', 'actions')->name('actions');
            Route::get('/export', 'export')->name('export');
            Route::get('/{id}', 'show')->name('show');
            Route::put('/{id}/update', 'update')->name('update');
            Route::put('/{id}/update-kind-cash', 'updateKindCash')->name('updateKindCash');
            Route::post('/{id}/test/manually-renew', 'testManuallyRenew')->name('testManuallyRenew');
        });

        // Causes
        Route::controller(CauseController::class)->prefix('/causes')->name('causes.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
        });
    });

    // Crm Views
    Route::prefix('/crm')->group(function () {
    });
});

Route::controller(WebhookController::class)->prefix('/webhooks')->name('webhooks.')->group(function () {
    Route::post('/printforia', 'printforiaWebhook')->name('printforia');
});
