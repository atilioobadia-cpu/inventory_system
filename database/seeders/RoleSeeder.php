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
                    'name'        => 'Super Admin',
                    'slug'        => 'super-admin',
                    'description' => 'Full access to all system features and settings',
                    'is_system'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Manager',
                    'slug'        => 'manager',
                    'description' => 'Can manage most operational features',
                    'is_system'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Cashier',
                    'slug'        => 'cashier',
                    'description' => 'Sales and customer focused operations',
                    'is_system'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Warehouse',
                    'slug'        => 'warehouse',
                    'description' => 'Stock management and purchase operations',
                    'is_system'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Accountant',
                    'slug'        => 'accountant',
                    'description' => 'Expense tracking and financial reporting',
                    'is_system'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Viewer',
                    'slug'        => 'viewer',
                    'description' => 'Read-only access to system data',
                    'is_system'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
            ];

            Role::insert($roles);
        });
    }
}
