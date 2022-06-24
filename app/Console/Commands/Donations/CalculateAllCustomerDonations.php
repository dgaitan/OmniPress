<?php

namespace App\Console\Commands\Donations;

use App\Models\WooCommerce\Customer;
use App\Services\DonationsService;
use Illuminate\Console\Command;

class CalculateAllCustomerDonations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kinja:calculate-user-donations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $offset = 0;
        $perPage = 100;
        $page = 1;
        $customers = Customer::skip($offset)->take($perPage)->get();

        while ($customers->isNotEmpty()) {
            $this->info(sprintf('Calculation Page #%s', $page));
            $page++;

            $customers->map(function ($customer) {
                DonationsService::calculateCustomerDonations($customer);
            });

            $offset = $offset + $perPage;
            $customers = Customer::skip($offset)->take($perPage)->get();
        }

        $this->info('Calculation finished');
    }
}
