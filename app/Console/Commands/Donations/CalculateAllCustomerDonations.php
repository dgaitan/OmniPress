<?php

namespace App\Console\Commands\Donations;

use App\Models\Causes\UserDonation;
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
        UserDonation::truncate();
        DonationsService::calculateAllCustomerDonations();
        $this->info('Calculation finished');
    }
}
