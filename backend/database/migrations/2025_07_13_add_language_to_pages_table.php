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
        Schema::table('pages', function (Blueprint $table) {
            $table->string('language', 5)->default('en')->after('slug');
            $table->index(['language', 'is_published']);
            $table->index(['slug', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex(['language', 'is_published']);
            $table->dropIndex(['slug', 'language']);
            $table->dropColumn('language');
        });
    }
}; 