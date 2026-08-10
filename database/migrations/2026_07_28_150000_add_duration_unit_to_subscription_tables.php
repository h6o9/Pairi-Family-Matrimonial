<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriptions', 'duration_unit')) {
                $table->string('duration_unit', 20)->default('days')->after('duration_days');
            }
            if (!Schema::hasColumn('subscriptions', 'payment_status')) {
                $table->string('payment_status', 20)->default('paid')->after('type');
            }
        });

        Schema::table('marriage_bureau_subscription_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('marriage_bureau_subscription_plans', 'duration_unit')) {
                $table->string('duration_unit', 20)->default('days')->after('duration_days');
            }
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('subscriptions', 'duration_unit')) {
                $table->dropColumn('duration_unit');
            }
            if (Schema::hasColumn('subscriptions', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
        });

        Schema::table('marriage_bureau_subscription_plans', function (Blueprint $table) {
            if (Schema::hasColumn('marriage_bureau_subscription_plans', 'duration_unit')) {
                $table->dropColumn('duration_unit');
            }
        });
    }
};
