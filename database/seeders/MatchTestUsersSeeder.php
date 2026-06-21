<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MatchTestUsersSeeder extends Seeder
{
    public function run(): void
    {
        $male = User::updateOrCreate(
            ['email' => 'hamza@test.com'],
            [
                'name' => 'Hamza',
                'phone' => '03001234567',
                'password' => Hash::make('Password@123'),
                'gender' => 'male',
                'birthday' => '1998-05-10',
                'country' => 'Pakistan',
                'city' => 'Lahore',
                'latitude' => 31.5204,
                'longitude' => 74.3587,
                'bio' => 'Looking for a life partner.',
                'qualification' => "Master's Degree",
                'job_title' => 'Software Engineer',
                'religion' => 'Islam',
                'marital_status' => 'Never Married',
                'interests' => ['Travel', 'Reading', 'Cooking'],
                'mother_tongue' => 'Urdu',
                'other_languages' => ['English', 'Punjabi'],
                'height' => '5 ft 10 inches',
                'community' => 'Rajput',
                'phone_verified' => true,
                'is_verified' => true,
                'profile_completed' => true,
                'profile_step' => 7,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $females = [
            [
                'name' => 'Jannat',
                'email' => 'jannat@test.com',
                'age' => 26,
                'city' => 'Lahore',
                'job_title' => 'Software Engineer',
                'qualification' => "Master's Degree",
                'interests' => ['Travel', 'Cooking', 'Reading', 'Yoga'],
                'bio' => 'A passionate traveler and avid reader.',
            ],
            [
                'name' => 'Aisha',
                'email' => 'aisha@test.com',
                'age' => 25,
                'city' => 'Lahore',
                'job_title' => 'Doctor',
                'qualification' => "Bachelor's Degree",
                'interests' => ['Travel', 'Reading'],
                'bio' => 'Doctor by profession, family oriented.',
            ],
            [
                'name' => 'Ayesha Khan',
                'email' => 'ayesha@test.com',
                'age' => 26,
                'city' => 'Lahore',
                'job_title' => 'Software Engineer',
                'qualification' => "Master's Degree",
                'interests' => ['Travel', 'Cooking', 'Reading'],
                'bio' => 'A passionate traveler and avid reader who loves exploring new cultures.',
            ],
            [
                'name' => 'Fatima Khan',
                'email' => 'fatima@test.com',
                'age' => 21,
                'city' => 'Lahore',
                'job_title' => 'Teacher',
                'qualification' => "Bachelor's Degree",
                'interests' => ['Cooking', 'Yoga'],
                'bio' => 'Teacher who loves kids and family values.',
            ],
        ];

        foreach ($females as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'phone' => '0300' . random_int(1000000, 9999999),
                    'password' => Hash::make('Password@123'),
                    'gender' => 'female',
                    'birthday' => now()->subYears($data['age'])->format('Y-m-d'),
                    'country' => 'Pakistan',
                    'city' => $data['city'],
                    'latitude' => 31.5204,
                    'longitude' => 74.3587,
                    'bio' => $data['bio'],
                    'qualification' => $data['qualification'],
                    'job_title' => $data['job_title'],
                    'religion' => 'Islam',
                    'marital_status' => 'Never Married',
                    'interests' => $data['interests'],
                    'mother_tongue' => 'Urdu',
                    'other_languages' => ['Urdu', 'English'],
                    'height' => '5 ft 4 inches',
                    'community' => 'Sunni',
                    'phone_verified' => true,
                    'is_verified' => true,
                    'profile_completed' => true,
                    'profile_step' => 7,
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command?->info('Match test users seeded. Login: hamza@test.com / Password@123');
    }
}
