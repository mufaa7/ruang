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
        Schema::create('duck_dialogues', function (Blueprint $table) {
            $table->id();
            $table->string('event')->index(); // idle, pomodoro_finish, export, random, dll
            $table->string('mood')->nullable(); // santai, nyinyir, dll
            $table->text('content'); // the dialogue itself
            $table->timestamp('last_used_at')->nullable()->index(); // track when it was last said
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duck_dialogues');
    }
};
