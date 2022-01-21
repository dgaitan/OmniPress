<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sync;
use Carbon\Carbon;

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


        $sync = Sync::create([
            'name' => sprintf('%s sync', ucwords($content_type)),
            'status' => Sync::FAILED,
            'content_type' => $content_type,
            'user_id' => 1,
            'description' => '',
            'intents' => 1
        ]);

        $firstSyncLog = sprintf(
            'Sync executed from console at %s',
            Carbon::now()->format('F j, Y @ H:i:s')
        );
        $sync->add_log($firstSyncLog);

        $this->info(sprintf('Starting sync of %s', $content_type));

        $sync->execute();
        $this->info('Sync completed!');
    }
}
