<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('makalah_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('makalah_id')->constrained('makalah')->cascadeOnDelete();

            $table->string('title');           // "Pendahuluan", "Tinjauan Pustaka", dll
            $table->longText('content')->nullable(); // HTML dari rich text editor
            $table->integer('order')->default(0);

            // Jenis section
            $table->enum('type', [
                'cover',           // Halaman Judul (auto dari makalah)
                'kata_pengantar',  // Kata Pengantar
                'bab',             // Bab I, II, III, dst
                'penutup',         // Kesimpulan & Saran
                'lampiran',        // Lampiran
            ])->default('bab');

            $table->integer('bab_number')->nullable(); // nomor bab (1, 2, 3...)
            $table->json('sub_sections')->nullable();
            // Array of {title, content, number: "1.1"} untuk sub-bab

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('makalah_chapters');
    }
};
