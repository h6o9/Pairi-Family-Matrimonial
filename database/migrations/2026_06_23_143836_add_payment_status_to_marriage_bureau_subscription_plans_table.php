<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('marriage_bureau_subscription_plans', function (Blueprint $table) {
            $table->string('payment_status')->default('paid')->after('price'); // free or paid
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marriage_bureau_subscription_plans', function (Blueprint $table) {
            //
        });
    }
};
