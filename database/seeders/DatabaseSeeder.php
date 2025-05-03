<?php

namespace Database\Seeders;

use App\Models\BasicInfo;
use App\Models\Location;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Amsiam',
        //     'email' => 'test@gmail.com',
        // ]);

        // BasicInfo::create([
        //     "user_id" => 1,
        //     "dob" => "2000-12-13",
        //     "height" => 5.5,
        //     "weight" => 65,
        //     "bio" => "I am amsiam",
        // ]);

        // Location::create([
        //     "user_id" => 1
        // ]);
    }
}
