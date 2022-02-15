<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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

        // Admin Perms
        $adminPerms = [
            'add_user', 'assign_roles', 'run_sync', 'manage_api_tokens',
            'see_roles', 'add_external_user',...$kindhumansTeam
        ];

        // Super Admin Abilities
        $superAdminPerms = [
            'maintenance', 'add_roles', 'add_super_admin',
            'edit_roles', ...$adminPerms
        ];

        $permissionsByRole = [
            'super_admin' => $superAdminPerms,
            'admin' => $adminPerms,
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
    }
}
