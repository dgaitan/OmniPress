<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Models\User;

class AssignRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kinja:become-super-admin 
                            {user_email : The user email to assign role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a role to an user';

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
        try {
            $user = User::whereEmail($this->argument('user_email'))->firstOrFail();
            $user->assignRole('super_admin');
            $this->info(sprintf('%s now is a super admin', $user->email));
        } catch(Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
