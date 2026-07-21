<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $roles = [
                [
                    'name'        => 'Admin',
                    'slug'        => 'admin',
                    'description' => 'Full access to all system features and settings',
                    'is_system'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'User',
                    'slug'        => 'user',
                    'description' => 'Standard user with POS and basic operational access',
                    'is_system'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
            ];

            Role::insert($roles);
        });
    }
}
