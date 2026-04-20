<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        $admin = User::updateOrCreate(
            ['email' => 'fauziahmad@gmail.com'],
            [
                'name' => 'Ahmad Fauzi',
                'password' => Hash::make('kelompok3'),
            ]
        );

        // USER
        $user = User::updateOrCreate(
            ['email' => 'izaldev@gmail.com'],
            [
                'name' => 'Syahrizaldev',
                'password' => Hash::make('password'),
            ]
        );

        // Ambil role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'user']);

        // Ambil semua permission
        $permissions = Permission::all();

        // Assign permission ke admin
        $adminRole->syncPermissions($permissions);

        // Assign role ke masing-masing user
        $admin->assignRole($adminRole);
        $user->assignRole($userRole);
    }
}