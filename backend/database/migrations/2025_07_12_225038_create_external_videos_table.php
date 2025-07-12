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
        Schema::create('external_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('platform'); // e.g. YouTube, Vimeo
            $table->string('video_url');
            $table->string('thumbnail_url')->nullable();
            $table->text('description')->nullable();
            $table->integer('view_count')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_videos');
    }
};
