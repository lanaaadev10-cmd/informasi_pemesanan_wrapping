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

            // pesanans
            'pesanans index',
            'pesanans create',
            'pesanans edit',
            'pesanans delete',

            // bookings
            'bookings index',
            'bookings create',
            'bookings edit',
            'bookings delete',

            // layanans
            'layanans index',
            'layanans create',
            'layanans edit',
            'layanans delete',

            // galeris
            'galeris index',
            'galeris create',
            'galeris edit',
            'galeris delete',

            // ratings
            'ratings index',
            'ratings create',
            'ratings edit',
            'ratings delete',

            // settings & laporan
            'settings edit',
            'laporan view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }
    }
}