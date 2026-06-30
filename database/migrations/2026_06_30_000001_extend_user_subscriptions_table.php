<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('payment_screenshot');
            $table->timestamp('starts_at')->nullable()->after('payment_method');
            $table->timestamp('expires_at')->nullable()->after('starts_at');
            $table->timestamp('cancelled_at')->nullable()->after('expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'starts_at', 'expires_at', 'cancelled_at']);
        });
    }
};
