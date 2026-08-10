<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@pairifamily.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'is_super_admin' => 1,
                'status' => 'active',
            ]
        );

        $this->call(SubscriptionPlansSeeder::class);
    }
}
