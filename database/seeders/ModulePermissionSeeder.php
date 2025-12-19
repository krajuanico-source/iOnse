<?php

namespace Database\Seeders;

use App\Models\Module;   
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ModulePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'User Management',
            'Position Management',
            'Hiring and Onboarding',
            'CPR Period Management',
            'CPR Validation',
            'CPR Management',
            'CPR Approval',
            'Data Export',
            'Data Import',
        ];

        $actions = ['view', 'create', 'edit'];

                foreach ($modules as $moduleName) {
            $module = Module::firstOrCreate(
                ['slug' => str()->slug($moduleName)],
                ['name' => $moduleName]
            );

            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => $module->slug . '.' . $action,
                    'guard_name' => 'web',
                ]);
            }
        }
    }
}
