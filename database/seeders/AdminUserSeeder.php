<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        $existingAdmin = User::where('email', 'admin@engineersdiarybd.com')->first();

        if ($existingAdmin) {
            $this->command->info('Admin user already exists!');
            return;
        }

        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@engineersdiarybd.com',
            'password' => Hash::make('Admin@123'), // Change this password after first login
            'is_admin' => true,
            'email_verified_at' => now(),
            'profile_verified_at' => now(),
        ]);

        // Create basic profile records for admin to avoid null reference errors
        $admin->basicInfo()->create([
            'dob' => '1990-01-01',
            'bio' => 'System Administrator',
            'gender' => 'MALE',
            'religion' => 'ISLAM',
            'height' => 0,
            'weight' => 0,
            'nid' => 'N/A',
            'blood_group' => 'N/A',
            'student_id' => 'N/A',
            'university' => 'N/A',
        ]);

        $admin->physical_attr()->create();
        $admin->personal()->create();
        $admin->lifestyle()->create();
        $admin->language()->create();
        $admin->family()->create();
        $admin->education()->create();
        $admin->location()->create();
        $admin->hobby()->create();
        $admin->partnerExpectation()->create();
        $admin->parmanent()->create();
        $admin->spiritualSocial()->create();

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@engineersdiarybd.com');
        $this->command->info('Password: Admin@123');
        $this->command->warn('⚠️  Please change the password after first login!');
    }
}
