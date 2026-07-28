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
        Schema::table('makalah_references', function (Blueprint $table) {
            $table->text('raw_citation')->nullable()->after('judul');
            $table->string('penulis')->nullable()->change();
            $table->string('judul')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('makalah_references', function (Blueprint $table) {
            $table->dropColumn('raw_citation');
            $table->string('penulis')->nullable(false)->change();
            $table->string('judul')->nullable(false)->change();
        });
    }
};
