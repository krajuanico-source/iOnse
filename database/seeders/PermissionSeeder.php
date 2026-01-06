<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // --------------------------
        // 1. Define Modules & Actions
        // --------------------------
        $modules = [
            'user_management',
            'user_permission',
            'position_management',
            'hiring_onboarding',
            'cpr_period_management',
            'cpr_validation',
            'cpr_management',
            'cpr_approval',
            'data_export',
            'data_import',
        ];

        $actions = ['view', 'create', 'edit'];

        // --------------------------
        // 2. Create Permissions
        // --------------------------
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$module}.{$action}",
                    'guard_name' => 'web',
                ]);
            }
        }

        // --------------------------
        // 3. Create Roles
        // --------------------------
        $roles = [
            'HR-PLANNING',
            'EMPLOYEE',
            
        ];

        $createdRoles = [];
        foreach ($roles as $roleName) {
            $createdRoles[$roleName] = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);
        }

        // --------------------------
        // 4. Assign Permissions to Roles
        // --------------------------

        // HR-PLANNING gets all permissions
        $createdRoles['HR-PLANNING']->syncPermissions(Permission::all());

        // EMPLOYEE gets only view permissions
        $employeePermissions = [];
        foreach ($modules as $module) {
            $employeePermissions[] = "{$module}.view";
        }
        $createdRoles['EMPLOYEE']->syncPermissions($employeePermissions);

        $this->command->info('✅ Roles and Permissions Seeder completed successfully!');
    }
}
