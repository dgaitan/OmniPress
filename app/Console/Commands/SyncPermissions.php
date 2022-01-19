<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

class SyncPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kinja:sync-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the app roles and permissions';

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
        $this->info('Starting permissions sync...');
        
        Artisan::call('db:seed --class=UserRolesSeeder');

        $this->info('Sync Completed!');

        $roles = Role::all();
        $rolesInfo = [];

        foreach ($roles as $role) {
            $rolesInfo[] = [$role->name, $role->permissions->count()];
        }

        $this->table(
            ['Role Name', 'Total Permissions'],
            $rolesInfo
        );

    }
}
