<?php

namespace App\Console\Commands;

use App\Actions\PreSales\ReleaseOrders;
use Illuminate\Console\Command;

class ReleasePreOrders extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kinja:release-pre-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Maybe Release Pre ORders';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        ReleaseOrders::run();
        return Command::SUCCESS;
    }
}
