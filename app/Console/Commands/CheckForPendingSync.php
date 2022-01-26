<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sync;
use App\Jobs\WooCommerceSyncJob;

class CheckForPendingSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kinja:check-pending-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for pending sync';

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
        $sync = Sync::where('status', '=', Sync::PENDING)->where('current_page', '>', 1)->get();

        $this->info(sprintf("Pending syncs: %s", $sync->count()));

        if ($sync) {
            foreach ($sync as $s) {
                WooCommerceSyncJob::dispatch($s->id);
            }
        }
    }
}
