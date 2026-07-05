<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
Role::firstOrCreate([
    'name' => 'admin',
    'guard_name' => 'web'
]);

Role::firstOrCreate([
    'name' => 'user',
    'guard_name' => 'web'
]);
    }
}

