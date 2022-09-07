<?php

namespace App\Console\Commands;

use App\Actions\Memberships\CheckMembershipAction;
use App\Jobs\Memberships\RenewalJob;
use Illuminate\Console\Command;

class RenewMembershipTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kinja:renew-membership-task
                            {--everything=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze the memberships and maybe renew it';

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
        $everything = false;

        if ($this->option('everything')) {
            $everything = $this->option('everything');
        }

        CheckMembershipAction::dispatch(allMembership: true);
        // RenewalJob::dispatch($everything);
        $this->info(sprintf('Looped: %s', $everything ? 'Everything' : 'Only Needed'));
        $this->info('Task Queued');
    }
}
