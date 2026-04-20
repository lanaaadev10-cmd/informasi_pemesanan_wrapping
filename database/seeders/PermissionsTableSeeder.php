<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // users
            'users index',
            'users create',
            'users edit',
            'users delete',

            // roles
            'roles index',
            'roles create',
            'roles edit',
            'roles delete',

            // permissions
            'permissions index',
            'permissions create',
            'permissions edit',
            'permissions delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }
    }
}