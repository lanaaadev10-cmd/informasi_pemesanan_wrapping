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
                'email_verified_at' => now(),
            ]
        );

        // USER
        $user = User::updateOrCreate(
            ['email' => 'izaldev@gmail.com'],
            ['name' => 'Syahrizaldev','password' => Hash::make('password'),]
        );

        // Pastikan role ada
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // Ambil semua permission dan sync ke admin
        $adminRole->syncPermissions(Permission::all());

        // Assign role ke masing-masing user
        if (!$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }
        if (!$user->hasRole('user')) {
            $user->assignRole($userRole);
        }
    }
}
