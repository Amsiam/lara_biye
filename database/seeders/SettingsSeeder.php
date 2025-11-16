<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Contact Information
            ['key' => 'contact.address', 'value' => 'House:2, Road:32, Dhanmondi', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact.phone', 'value' => '+8809611489040', 'type' => 'phone', 'group' => 'contact'],
            ['key' => 'contact.email', 'value' => 'connect@engineersdiarybd.com', 'type' => 'email', 'group' => 'contact'],
            ['key' => 'contact.whatsapp', 'value' => '8801911676540', 'type' => 'phone', 'group' => 'contact'],

            // Social Media
            ['key' => 'social.facebook', 'value' => 'https://www.facebook.com/Matrimony.ED/', 'type' => 'url', 'group' => 'social'],
            ['key' => 'social.instagram', 'value' => 'https://www.instagram.com/engineersdiarybd/', 'type' => 'url', 'group' => 'social'],

            // Statistics
            ['key' => 'stats.total_reviews', 'value' => '1200', 'type' => 'number', 'group' => 'stats'],
            ['key' => 'stats.review_average', 'value' => '4.7', 'type' => 'number', 'group' => 'stats'],
            ['key' => 'stats.total_marriages', 'value' => '1600', 'type' => 'number', 'group' => 'stats'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
