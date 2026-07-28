<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('makalah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Identitas makalah
            $table->string('judul');
            $table->string('sub_judul')->nullable();
            $table->string('nama_penulis');
            $table->string('nim')->nullable();
            $table->string('nama_dosen')->nullable();
            $table->string('mata_kuliah')->nullable();
            $table->string('universitas')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('prodi')->nullable();
            $table->string('kota')->nullable();
            $table->string('tahun', 4)->nullable();
            $table->string('logo_path')->nullable();
            $table->string('logo_url')->nullable();   // external URL logo

            // Format & pengaturan
            $table->json('settings')->nullable();
            // {
            //   "font_size": 12,
            //   "font_family": "Times New Roman",
            //   "line_height": 1.5,
            //   "margin_top": 4,     // cm
            //   "margin_right": 3,
            //   "margin_bottom": 3,
            //   "margin_left": 4,
            //   "page_number_style": "mixed",  // mixed | arabic | none
            //   "citation_style": "apa"         // apa | chicago | custom
            // }

            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('makalah');
    }
};
