<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class generatePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will generate the default roles and permissions';

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
        // $guest = ['can_see_basic', 'can_edit_profile'];
        // $analyzer = ['can_see_analytics', 'can_see_insigths'];
        // $manager = ['can_add_user', 'can_delete_user'];
        // $admin_perms = [
        //     'can_add_user', 'can_delete_user',
        //     'can_add_org', 'can_delete_org',
        //     'can_create_service', 'can_delete_service',
        //     'can_sync', 'ca'
        // ];
        // $roles = [
        //     'super_admin', [],
        //     'admin' => [],
        //     'manager' => [],
        //     'analyzer' => [],
        //     'guest' => []
        // ];
    }
}
