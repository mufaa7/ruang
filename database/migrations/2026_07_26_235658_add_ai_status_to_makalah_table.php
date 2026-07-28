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
        Schema::table('makalah', function (Blueprint $table) {
            $table->string('ai_status')->nullable(); // queued, processing_outline, processing_chapter, completed, failed
            $table->text('ai_progress')->nullable(); // Status text
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('makalah', function (Blueprint $table) {
            $table->dropColumn(['ai_status', 'ai_progress']);
        });
    }
};
