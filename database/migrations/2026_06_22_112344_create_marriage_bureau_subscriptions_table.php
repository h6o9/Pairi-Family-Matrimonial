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
        Schema::create('marriage_bureau_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('marriage_bureau_id');
            $table->unsignedBigInteger('marriage_bureau_subscription_plan_id');
            $table->string('status')->default('free'); // free, paid, verified
            $table->string('payment_screenshot')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marriage_bureau_subscriptions');
    }
};
