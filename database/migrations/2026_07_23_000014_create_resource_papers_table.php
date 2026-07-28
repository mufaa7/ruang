<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pivot resource <-> paper
        Schema::create('resource_papers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete();
            $table->foreignId('paper_id')->constrained('papers')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['resource_id', 'paper_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_papers');
    }
};
