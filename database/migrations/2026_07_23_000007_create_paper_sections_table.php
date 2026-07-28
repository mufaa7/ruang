<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paper_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paper_id')->constrained('papers')->cascadeOnDelete();
            $table->string('title');
            $table->longText('content')->nullable();
            $table->integer('order')->default(0);
            $table->enum('type', ['introduction', 'body', 'conclusion', 'references', 'appendix', 'custom'])->default('body');
            $table->json('snapshot')->nullable(); // content snapshot untuk versioning
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paper_sections');
    }
};
