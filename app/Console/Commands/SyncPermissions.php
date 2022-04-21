<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $kindhumansTeam = [
            'see_users',
            'see_memberships', 'analyze_memberships', 'export_memberships', 'edit_memberships',
            'see_customers', 'analyze_customers', 'export_customers',
            'see_coupons', 'analyze_coupons', 'export_coupons',
            'see_orders', 'analyze_orders', 'export_orders',
            'see_products', 'analyze_products', 'export_products',
            'see_payment_methods'
        ];

        $qaPerms = [
            'force_membership_renewals', ...$kindhumansTeam
        ];

        // Admin Perms
        $adminPerms = [
            'add_user', 'assign_roles', 'run_sync', 'manage_api_tokens',
            'see_roles', 'add_external_user',...$qaPerms
        ];

        // Super Admin Abilities
        $superAdminPerms = [
            'maintenance', 'add_roles', 'add_super_admin',
            'edit_roles', 'admin_queues', ...$adminPerms
        ];

        $permissionsByRole = [
            'super_admin' => $superAdminPerms,
            'admin' => $adminPerms,
            'qa' => $qaPerms,
            'kindhumans_team' => $kindhumansTeam
        ];

        foreach ( $permissionsByRole as $role => $permissions ) {
            $role = Role::firstOrCreate(['name' => $role]);
            $perms = [];

            foreach ( $permissions as $perm ) {
                $perms[] = Permission::firstOrCreate([
                    'name' => $perm
                ]);
            }

            $role->givePermissionTo($perms);
        }

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
