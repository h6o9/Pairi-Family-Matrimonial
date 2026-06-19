<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('otp', 10)->nullable()->after('password');
            $table->timestamp('email_otp_expires_at')->nullable()->after('otp');
            $table->boolean('is_verified')->default(false)->after('email_verified_at');
            $table->string('phone_otp', 10)->nullable()->after('phone');
            $table->timestamp('phone_otp_expires_at')->nullable()->after('phone_otp');
            $table->boolean('phone_verified')->default(false)->after('phone_otp_expires_at');
            $table->string('reset_password_token')->nullable()->after('forget_password_token');
            $table->timestamp('reset_token_expires_at')->nullable()->after('reset_password_token');
            $table->string('reset_otp', 10)->nullable()->after('reset_token_expires_at');

            $table->string('country')->nullable()->after('address');
            $table->string('city')->nullable()->after('country');
            $table->string('qualification')->nullable();
            $table->string('field_of_study')->nullable();
            $table->string('university')->nullable();
            $table->string('graduation_year')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('job_title')->nullable();
            $table->string('company')->nullable();
            $table->string('monthly_income')->nullable();
            $table->string('residential_status')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('body_type')->nullable();
            $table->string('complexion')->nullable();
            $table->boolean('physical_disability')->default(false);
            $table->string('religion')->nullable();
            $table->string('community')->nullable();
            $table->string('sect')->nullable();
            $table->string('mother_tongue')->nullable();
            $table->json('other_languages')->nullable();
            $table->json('interests')->nullable();
            $table->json('photos')->nullable();
            $table->string('marital_status')->nullable();
            $table->boolean('profile_completed')->default(false);
            $table->unsignedTinyInteger('profile_step')->default(0);
            $table->string('social_provider')->nullable();
            $table->string('social_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'otp', 'email_otp_expires_at', 'is_verified',
                'phone_otp', 'phone_otp_expires_at', 'phone_verified',
                'reset_password_token', 'reset_token_expires_at', 'reset_otp',
                'country', 'city', 'qualification', 'field_of_study', 'university',
                'graduation_year', 'employment_type', 'job_title', 'company',
                'monthly_income', 'residential_status', 'height', 'weight',
                'body_type', 'complexion', 'physical_disability', 'religion',
                'community', 'sect', 'mother_tongue', 'other_languages',
                'interests', 'photos', 'marital_status', 'profile_completed',
                'profile_step', 'social_provider', 'social_id',
            ]);
        });
    }
};
