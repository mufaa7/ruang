<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('papers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('abstract')->nullable();
            $table->string('cover_image')->nullable();
            $table->enum('status', ['draft', 'in_review', 'published', 'archived'])->default('draft');
            $table->enum('visibility', ['private', 'public', 'subject_only'])->default('private');
            $table->json('settings')->nullable(); // custom settings
            $table->json('metadata')->nullable(); // keywords, etc
            $table->integer('view_count')->default(0);
            $table->integer('download_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('papers');
    }
};
