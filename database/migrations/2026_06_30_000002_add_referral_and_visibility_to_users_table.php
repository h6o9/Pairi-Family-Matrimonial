<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('referred_by')->nullable()->after('reward_points')->constrained('users')->nullOnDelete();
            $table->boolean('profile_photo_visible')->default(true)->after('referred_by');
            $table->boolean('additional_photos_visible')->default(true)->after('profile_photo_visible');
            $table->timestamp('profile_boost_until')->nullable()->after('additional_photos_visible');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referred_by']);
            $table->dropColumn(['referred_by', 'profile_photo_visible', 'additional_photos_visible', 'profile_boost_until']);
        });
    }
};
