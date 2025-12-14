<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmailTemplate::updateOrCreate(
            ['key' => 'profile_image_reminder'],
            [
                'name' => 'Profile Image Reminder',
                'subject' => 'Complete your profile to get noticed!',
                'content' => '<h2>Hello {{ name }},</h2>
<p>We noticed you haven\'t uploaded a profile picture yet. Did you know that profiles with photos get 10x more visibility?</p>
<p>Upload your photo now to find your perfect match faster!</p>
<div style="text-align: center; margin: 30px 0;">
    <a href="{{ profile_link }}" style="background-color: #e11d48; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">Upload Photo</a>
</div>
<p>Best regards,<br>The Engineers Matrimony Team</p>',
                'placeholders' => '{{ name }}, {{ profile_link }}',
            ]
        );

        EmailTemplate::updateOrCreate(
            ['key' => 'welcome_community'],
            [
                'name' => 'Welcome Community',
                'subject' => 'Join our Engineering Community!',
                'content' => '<h2>Welcome to the Community, {{ name }}!</h2>
<p>It\'s been 3 days since you joined us. We\'d love to have you in our exclusive community groups.</p>
<ul>
    <li>Join our <a href="#" style="color: #e11d48;">Facebook Page</a></li>
    <li>Join our <a href="#" style="color: #e11d48;">Community Group</a></li>
</ul>
<p>Connect with other members and stay updated!</p>
<p>Best regards,<br>The Engineers Matrimony Team</p>',
                'placeholders' => '{{ name }}',
            ]
        );

        EmailTemplate::updateOrCreate(
            ['key' => 'profile_completion_2month'],
            [
                'name' => 'Profile Completion (2 Month)',
                'subject' => 'It\'s been 2 months! Complete your profile.',
                'content' => '<h2>Hello {{ name }},</h2>
<p>It\'s been 2 months since you joined us. Adding more details to your profile significantly increases your chances of finding the perfect match.</p>
<p>Complete your profile today!</p> 
<div style="text-align: center; margin: 30px 0;">
    <a href="{{ profile_link }}" style="background-color: #e11d48; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">Update Profile</a>
</div>
<p>Best regards,<br>The Engineers Matrimony Team</p>',
                'placeholders' => '{{ name }}, {{ profile_link }}',
            ]
        );

        EmailTemplate::updateOrCreate(
            ['key' => 'profile_completion_4month'],
            [
                'name' => 'Profile Completion (4 Month)',
                'subject' => '4 months gone! Find your match today.',
                'content' => '<h2>Hello {{ name }},</h2>
<p>Time flies! It\'s been 4 months. Don\'t let your potential match scroll past your profile because it\'s incomplete.</p>
<div style="text-align: center; margin: 30px 0;">
    <a href="{{ profile_link }}" style="background-color: #e11d48; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">Update Profile</a>
</div>
<p>Best regards,<br>The Engineers Matrimony Team</p>',
                'placeholders' => '{{ name }}, {{ profile_link }}',
            ]
        );

        EmailTemplate::updateOrCreate(
            ['key' => 'profile_completion_6month'],
            [
                'name' => 'Profile Completion (6 Month)',
                'subject' => 'Half a year! Don\'t miss out.',
                'content' => '<h2>Hello {{ name }},</h2>
<p>It\'s been 6 months. We want to help you find your soulmate. A complete profile is the key.</p>
<div style="text-align: center; margin: 30px 0;">
    <a href="{{ profile_link }}" style="background-color: #e11d48; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">Update Profile</a>
</div>
<p>Best regards,<br>The Lara Biye Team</p>',
                'placeholders' => '{{ name }}, {{ profile_link }}',
            ]
        );
    }
}
