<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('makalah_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('makalah_id')->constrained('makalah')->cascadeOnDelete();

            $table->enum('type', ['buku', 'jurnal', 'web', 'skripsi', 'lainnya'])->default('buku');
            $table->string('penulis');          // "Sudirman, A." atau "Doe, J., & Smith, B."
            $table->string('judul');
            $table->string('tahun', 4)->nullable();
            $table->string('penerbit')->nullable();
            $table->string('kota_terbit')->nullable();
            $table->string('nama_jurnal')->nullable();
            $table->string('volume')->nullable();
            $table->string('nomor')->nullable();
            $table->string('halaman')->nullable(); // "12-25"
            $table->text('url')->nullable();
            $table->string('tanggal_akses')->nullable();
            $table->string('doi')->nullable();
            $table->integer('order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('makalah_references');
    }
};
