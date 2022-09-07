<?php

namespace App\Providers;

use App\Models\Printforia\PrintforiaOrder;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Product;
use App\Observers\OrderObserver;
use App\Observers\PrintforiaOrderObserver;
use App\Observers\ProductObserver;
use App\Services\WooCommerce\WooCommerceService;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->singleton(
            abstract: WooCommerceService::class,
            concrete: fn () => new WooCommerceService(
                domain: env('WOO_CUSTOMER_DOMAIN', ''),
                key: env('WOO_CUSTOMER_KEY', ''),
                secret: env('WOO_CUSTOMER_SECRET', '')
            )
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Cashier::useCustomerModel(Customer::class);
        Order::observe(OrderObserver::class);
        Product::observe(ProductObserver::class);
        PrintforiaOrder::observe(PrintforiaOrderObserver::class);

        LogViewer::auth(function ($request) {
            return $request->user() && in_array($request->user()->email, [
                'dgaitan@kindhumans.com',
            ]);
        });
    }
}
