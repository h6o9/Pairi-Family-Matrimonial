<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_interests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('to_user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('action', ['interest', 'pass'])->default('interest');
            $table->timestamps();

            $table->unique(['from_user_id', 'to_user_id']);
            $table->index(['to_user_id', 'action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_interests');
    }
};
