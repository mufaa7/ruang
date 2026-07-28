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
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->string('type')->default('pg')->after('quiz_id');
            $table->json('options')->nullable()->change();
            $table->text('correct_answer')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->json('options')->nullable(false)->change();
            $table->string('correct_answer', 5)->nullable(false)->change();
        });
    }
};
