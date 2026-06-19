<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('terms_accepted_at')->nullable()->after('social_id');
            $table->timestamp('otp_resend_available_at')->nullable()->after('email_otp_expires_at');
            $table->timestamp('phone_otp_resend_available_at')->nullable()->after('phone_otp_expires_at');
            $table->boolean('reset_code_verified')->default(false)->after('reset_otp');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'terms_accepted_at',
                'otp_resend_available_at',
                'phone_otp_resend_available_at',
                'reset_code_verified',
            ]);
        });
    }
};
