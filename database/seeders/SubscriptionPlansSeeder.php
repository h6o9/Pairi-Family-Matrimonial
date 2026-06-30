<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionPlansSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'price' => 0,
                'duration_days' => 3650,
                'type' => 'Free',
                'badge' => null,
                'features' => [
                    'basic_search' => true,
                    'chat_limit' => 3,
                    'boosts_per_month' => 0,
                    'super_likes_per_day' => 0,
                    'see_who_liked' => false,
                    'verified_badge' => false,
                ],
                'status' => 'active',
            ],
            [
                'name' => 'VIP Plan',
                'price' => 2499,
                'duration_days' => 30,
                'type' => 'VIP',
                'badge' => 'VIP',
                'features' => [
                    'basic_search' => true,
                    'chat_limit' => null,
                    'boosts_per_month' => 3,
                    'super_likes_per_day' => 5,
                    'see_who_liked' => true,
                    'verified_badge' => true,
                ],
                'status' => 'active',
            ],
            [
                'name' => 'VVIP Plan',
                'price' => 3999,
                'duration_days' => 30,
                'type' => 'VVIP',
                'badge' => 'VVIP',
                'features' => [
                    'basic_search' => true,
                    'chat_limit' => null,
                    'boosts_per_month' => null,
                    'super_likes_per_day' => null,
                    'see_who_liked' => true,
                    'verified_badge' => true,
                ],
                'status' => 'active',
            ],
        ];

        foreach ($plans as $plan) {
            Subscription::updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }
}
