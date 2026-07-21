<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $hashedPassword = Hash::make('password');

            $users = [
                [
                    'name'       => 'Admin User',
                    'email'      => 'admin@mtokoma.co.tz',
                    'password'   => $hashedPassword,
                    'role_id'    => Role::where('slug', 'admin')->first()?->id,
                    'is_active'  => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name'       => 'User',
                    'email'      => 'user@mtokoma.co.tz',
                    'password'   => $hashedPassword,
                    'role_id'    => Role::where('slug', 'user')->first()?->id,
                    'is_active'  => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            User::insert($users);
        });
    }
}
