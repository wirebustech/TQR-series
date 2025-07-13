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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('payment_method', 50);
            $table->string('payment_intent_id')->unique();
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->json('metadata')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['payment_intent_id']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
}; 