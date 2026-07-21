<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $users = [
                [
                    'name'       => 'Admin User',
                    'email'      => 'admin@mtokoma.co.tz',
                    'password'   => 'password',
                    'role_id'    => Role::where('slug', 'super-admin')->first()?->id,
                    'is_active'  => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name'       => 'Manager User',
                    'email'      => 'manager@mtokoma.co.tz',
                    'password'   => 'password',
                    'role_id'    => Role::where('slug', 'manager')->first()?->id,
                    'is_active'  => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name'       => 'Cashier User',
                    'email'      => 'cashier@mtokoma.co.tz',
                    'password'   => 'password',
                    'role_id'    => Role::where('slug', 'cashier')->first()?->id,
                    'is_active'  => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            User::insert($users);
        });
    }
}
