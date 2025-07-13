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
        Schema::create('webinars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('scheduled_at');
            $table->integer('duration')->default(60)->comment('Duration in minutes');
            $table->integer('max_attendees')->nullable();
            $table->string('platform')->default('zoom')->comment('zoom, teams, meet, webex, other');
            $table->string('meeting_url')->nullable();
            $table->string('tags')->nullable();
            $table->enum('status', ['draft', 'published', 'live', 'completed'])->default('draft');
            $table->boolean('requires_registration')->default(true);
            $table->boolean('is_public')->default(true);
            $table->string('image')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['status', 'scheduled_at']);
            $table->index(['is_public', 'scheduled_at']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webinars');
    }
};
