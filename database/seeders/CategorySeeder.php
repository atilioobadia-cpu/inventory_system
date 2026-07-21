<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $categories = [
                [
                    'name'        => 'Brake System',
                    'slug'        => 'brake-system',
                    'description' => 'Brake pads, discs, calipers, and cables',
                    'sort_order'  => 1,
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Engine Parts',
                    'slug'        => 'engine-parts',
                    'description' => 'Pistons, cylinders, gaskets, and engine components',
                    'sort_order'  => 2,
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Electrical',
                    'slug'        => 'electrical',
                    'description' => 'Spark plugs, CDI units, wiring harnesses, and electrical components',
                    'sort_order'  => 3,
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Suspension',
                    'slug'        => 'suspension',
                    'description' => 'Front forks, rear shocks, and suspension components',
                    'sort_order'  => 4,
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Exhaust',
                    'slug'        => 'exhaust',
                    'description' => 'Exhaust pipes, mufflers, and exhaust components',
                    'sort_order'  => 5,
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Body & Frame',
                    'slug'        => 'body-frame',
                    'description' => 'Fairings, seats, fuel tanks, and frame parts',
                    'sort_order'  => 6,
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Transmission',
                    'slug'        => 'transmission',
                    'description' => 'Chains, sprockets, clutch plates, and gearbox components',
                    'sort_order'  => 7,
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Lighting',
                    'slug'        => 'lighting',
                    'description' => 'Headlights, taillights, indicators, and bulbs',
                    'sort_order'  => 8,
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Tools & Accessories',
                    'slug'        => 'tools-accessories',
                    'description' => 'Workshop tools, maintenance kits, and accessories',
                    'sort_order'  => 9,
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Lubricants & Fluids',
                    'slug'        => 'lubricants-fluids',
                    'description' => 'Engine oil, brake fluid, chain lube, and coolants',
                    'sort_order'  => 10,
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
            ];

            Category::insert($categories);
        });
    }
}
