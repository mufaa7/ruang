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
        Schema::table('makalah', function (Blueprint $table) {
            $table->string('jenis_dokumen')->nullable()->default('Makalah');
            $table->longText('kata_pengantar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('makalah', function (Blueprint $table) {
            $table->dropColumn(['jenis_dokumen', 'kata_pengantar']);
        });
    }
};
