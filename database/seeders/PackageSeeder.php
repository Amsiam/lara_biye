<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Package::create([
            'name' => 'Starter',
            'description' => 'Perfect for getting started',
            'connections' => 3,
            'price' => 100.00,
            'is_popular' => false,
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Popular',
            'description' => 'Most popular choice',
            'connections' => 10,
            'price' => 300.00,
            'is_popular' => true,
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Premium',
            'description' => 'Best value for serious seekers',
            'connections' => 25,
            'price' => 600.00,
            'is_popular' => false,
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Ultimate',
            'description' => 'Unlimited access for 30 days',
            'connections' => 100,
            'price' => 1500.00,
            'is_popular' => false,
            'is_active' => true,
        ]);
    }
}
