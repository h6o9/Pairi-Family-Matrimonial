<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('hobbies_interests')) {
            Schema::create('hobbies_interests', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();
            });
        }

        DB::table('hobbies_interests')->insertOrIgnore([
            ['name' => 'Reading', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Travelling', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cooking', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sports', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Music', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('hobbies_interests');
    }
};
