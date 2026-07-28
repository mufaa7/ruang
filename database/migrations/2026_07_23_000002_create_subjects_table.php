<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->nullable(); // kode mata kuliah, misal: CS101
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // emoji atau icon name
            $table->string('color', 7)->default('#6366f1'); // hex color
            $table->string('semester')->nullable(); // misal: "Semester 1"
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
