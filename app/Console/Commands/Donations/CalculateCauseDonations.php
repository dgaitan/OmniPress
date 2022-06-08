<?php

namespace App\Console\Commands\Donations;

use App\Models\CauseDonation;
use App\Models\WooCommerce\Order;
use App\Services\DonationsService;
use Illuminate\Console\Command;

class CalculateCauseDonations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kinja:calculateCauseDonations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $offset = 0;
        $perPage = 100;
        $page = 1;
        $orders = Order::skip($offset)->take($perPage)->get();
        CauseDonation::truncate();

        while ($orders->isNotEmpty()) {
            $this->info(sprintf("Calculation Page #%s", $page));
            $page++;

            $orders->map(function ($order) {
                DonationsService::addCauseDonation($order);
            });

            $offset = $offset + $perPage;
            $orders = Order::skip($offset)->take($perPage)->get();
        }

        $this->info("Calculation finished");
    }
}
