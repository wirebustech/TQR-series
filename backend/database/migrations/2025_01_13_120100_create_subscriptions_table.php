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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('stripe_subscription_id')->unique();
            $table->string('plan_id');
            $table->enum('plan_type', ['monthly', 'yearly']);
            $table->enum('status', ['active', 'canceled', 'past_due', 'unpaid', 'trialing'])->default('active');
            $table->timestamp('current_period_start');
            $table->timestamp('current_period_end');
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['stripe_subscription_id']);
            $table->index(['current_period_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
}; 