<?php

namespace App\Console\Commands\Donations;

use App\Models\Causes\OrderDonation;
use App\Services\DonationsService;
use Illuminate\Console\Command;

class CalculateOrderDonations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kinja:calculate-order-donations';

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
        OrderDonation::truncate();
        DonationsService::calculateOrderDonations();
        $this->info('Calculation finished');
    }
}
