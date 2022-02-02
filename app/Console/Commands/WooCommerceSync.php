<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Sync;
use App\Models\User;
use App\Tasks\WooCommerceTask;

class WooCommerceSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kinja:woocommerce-sync 
                            {content_type : Type of content to sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data from WooCommerce';

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
        $content_type = $this->argument('content_type');

        if (!$content_type) {
            return $this->error('Content type is necessary');
        }

        $user = User::whereEmail('dgaitan@kindhumans.com')->first();

        $sync = Sync::where('content_type', $content_type)->where('status', Sync::PENDING);

        if ($sync->exists()) {
            $sync = $sync->first();
        } else {
            $sync = Sync::initialize(
                $content_type, 
                $user, 
                "Sync Run from console"
            );
        }

        $this->info(sprintf('Starting sync of %s', $content_type));

        $task = (new WooCommerceTask($sync))->dispatch(['per_page' => env('KINDHUMANS_SYNC_PER_PAGE', 100)]);

        while ($sync->status === Sync::PENDING) {
            $this->info(sprintf('Starting sync of %s page %s', $content_type, $sync->current_page));
            $task = (new WooCommerceTask($sync))->dispatch(['per_page' => env('KINDHUMANS_SYNC_PER_PAGE', 100)]);            
        }
        
        $this->info('Sync completed!');
    }
}
