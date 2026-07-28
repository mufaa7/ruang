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
        Schema::table('flashcard_sets', function (Blueprint $table) {
            $table->string('type', 20)->default('latihan')->after('title');
        });

        Schema::create('flashcard_set_user_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flashcard_set_id')->constrained('flashcard_sets')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['flashcard_set_id', 'user_id'], 'fs_user_targets_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flashcard_set_user_targets');
        
        Schema::table('flashcard_sets', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
