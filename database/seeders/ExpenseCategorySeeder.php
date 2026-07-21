<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $categories = [
                [
                    'name'        => 'Rent',
                    'slug'        => 'rent',
                    'description' => 'Shop and warehouse rent',
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Utilities',
                    'slug'        => 'utilities',
                    'description' => 'Electricity, water, and internet bills',
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Salaries',
                    'slug'        => 'salaries',
                    'description' => 'Staff wages and benefits',
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Transportation',
                    'slug'        => 'transportation',
                    'description' => 'Delivery, fuel, and logistics costs',
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Office Supplies',
                    'slug'        => 'office-supplies',
                    'description' => 'Stationery, printer ink, and office materials',
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Marketing',
                    'slug'        => 'marketing',
                    'description' => 'Advertising and promotional expenses',
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Maintenance',
                    'slug'        => 'maintenance',
                    'description' => 'Shop and equipment maintenance and repairs',
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Other',
                    'slug'        => 'other',
                    'description' => 'Miscellaneous and uncategorized expenses',
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
            ];

            DB::table('expense_categories')->insert($categories);
        });
    }
}
