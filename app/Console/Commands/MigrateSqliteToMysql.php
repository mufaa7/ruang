<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateSqliteToMysql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:migrate-sqlite-to-mysql';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all data from the local SQLite database to the default MySQL database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration from SQLite to MySQL...');

        // Pastikan koneksi mysql sudah jadi default (lewat .env)
        if (config('database.default') !== 'mysql') {
            $this->error('Ubah DB_CONNECTION di .env menjadi mysql terlebih dahulu!');
            return;
        }

        try {
            DB::connection('sqlite')->getPdo();
        } catch (\Exception $e) {
            $this->error('Koneksi ke SQLite gagal. Pastikan database/database.sqlite ada.');
            return;
        }

        try {
            DB::connection('mysql')->getPdo();
        } catch (\Exception $e) {
            $this->error('Koneksi ke MySQL gagal. Cek settingan .env Anda.');
            return;
        }

        // Matikan foreign key checks di MySQL sementara agar insert tidak error berantakan
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');

        // Ambil semua tabel dari SQLite
        $tables = DB::connection('sqlite')->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");

        foreach ($tables as $tableInfo) {
            $table = $tableInfo->name;
            $this->info("Migrating table: $table");

            // Pastikan tabelnya ada di MySQL sebelum migrasi datanya
            if (!Schema::connection('mysql')->hasTable($table)) {
                $this->warn("Tabel $table tidak ada di MySQL. Lewati...");
                continue;
            }

            // Kosongkan tabel di MySQL
            DB::connection('mysql')->table($table)->truncate();

            // Ambil data dari SQLite per chunk untuk menghindari memory limit
            DB::connection('sqlite')->table($table)->orderByRaw('1')->chunk(500, function ($records) use ($table) {
                $data = [];
                foreach ($records as $record) {
                    $data[] = (array) $record;
                }
                
                if (!empty($data)) {
                    DB::connection('mysql')->table($table)->insert($data);
                }
            });
        }

        // Nyalakan kembali foreign key checks
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Migration completed successfully!');
    }
}
