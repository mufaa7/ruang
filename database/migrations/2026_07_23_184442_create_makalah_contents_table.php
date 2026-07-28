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
        Schema::create('makalah_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('makalah_id')->constrained('makalah')->onDelete('cascade');
            $table->integer('bab');
            $table->integer('sub')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('makalah_contents');
    }
};
