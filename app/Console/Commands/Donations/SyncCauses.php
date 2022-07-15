<?php

namespace App\Console\Commands\Donations;

use App\Models\Causes\Cause;
use App\Services\WooCommerce\WooCommerceService;
use Illuminate\Console\Command;

class SyncCauses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kinja:sync-causes';

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
        $api = WooCommerceService::make();
        $api->causes()->syncAll(perPage: 100);

        $this->info(sprintf(
            'Total Causes Synced: %s',
            Cause::count()
        ));
    }
}
