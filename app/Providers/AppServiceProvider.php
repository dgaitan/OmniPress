<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use App\Models\WooCommerce\Customer;
use App\Services\WooCommerce\WooCommerceService;

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
            concrete: fn() => new WooCommerceService(
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
    }
}
