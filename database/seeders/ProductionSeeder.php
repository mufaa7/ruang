<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Seeding RUANG untuk Production...');

        // ── 1. Tags ─────────────────────────────────────────────────────────
        $this->command->info('📌 Creating tags...');
        $tagData = [
            ['name' => 'Laravel', 'color' => '#ef4444'],
            ['name' => 'JavaScript', 'color' => '#f59e0b'],
            ['name' => 'Python', 'color' => '#3b82f6'],
            ['name' => 'Database', 'color' => '#8b5cf6'],
            ['name' => 'Machine Learning', 'color' => '#10b981'],
            ['name' => 'UI/UX', 'color' => '#ec4899'],
            ['name' => 'Algoritma', 'color' => '#6366f1'],
            ['name' => 'Jaringan', 'color' => '#06b6d4'],
            ['name' => 'Mobile Dev', 'color' => '#84cc16'],
            ['name' => 'Keamanan', 'color' => '#f97316'],
        ];

        foreach ($tagData as $tag) {
            Tag::firstOrCreate(
                ['name' => $tag['name']],
                [
                    'slug'  => Str::slug($tag['name']),
                    'color' => $tag['color'],
                ]
            );
        }

        // ── 2. Admin User ────────────────────────────────────────────────────
        $this->command->info('👑 Creating admin user...');
        
        // TODO: GANTI EMAIL DAN USERNAME DI BAWAH INI SESUAI KEINGINAN LU!
        User::firstOrCreate(
            ['email' => 'mufaa@ruang.com'],
            [
                'name'              => 'Mufaa',
                'username'          => 'mufaa',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'role'              => 'admin',
                'bio'               => 'Manchester',
            ]
        );

        // ── 3. Regular User (Custom) ─────────────────────────────────────────
        $this->command->info('🎓 Creating custom regular user...');
        
        User::firstOrCreate(
            ['email' => 'julieta@ruang.com'],
            [
                'name'              => 'Julieta Varelia Desmiranda',
                'username'          => 'julieta',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'role'              => 'student',
                'bio'               => 'Pengguna awal aplikasi RUANG',
            ]
        );

        $this->command->newLine();
        $this->command->info('✅ Production Seeder selesai dijalankan!');
    }
}
