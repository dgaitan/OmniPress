<?php

namespace App\Console\Commands\Customers;

use Illuminate\Console\Command;

class StripeAssignment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kinja:customer-stripe-assignment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign customer stripe payment methods from customer id';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $customers = \App\Models\WooCommerce\Customer::get();
        $customers->map(function($customer) {
            $__this = $customer->setPaymentMethodsFromCustomerId();
            $this->info(sprintf(
                'Customer %s -> updating status: %s',
                $customer->email,
                $__this
            ));
        });

    }
}
