<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubjectFactory extends Factory
{
    private static array $subjects = [
        ['name' => 'Algoritma & Pemrograman', 'code' => 'CS101', 'icon' => '💻', 'color' => '#6366f1'],
        ['name' => 'Basis Data', 'code' => 'CS201', 'icon' => '🗄️', 'color' => '#8b5cf6'],
        ['name' => 'Kalkulus Diferensial', 'code' => 'MATH101', 'icon' => '📐', 'color' => '#06b6d4'],
        ['name' => 'Fisika Dasar', 'code' => 'PHY101', 'icon' => '⚛️', 'color' => '#10b981'],
        ['name' => 'Bahasa Indonesia', 'code' => 'BHS101', 'icon' => '📖', 'color' => '#f59e0b'],
        ['name' => 'Struktur Data', 'code' => 'CS202', 'icon' => '🌲', 'color' => '#ef4444'],
        ['name' => 'Jaringan Komputer', 'code' => 'CS301', 'icon' => '🌐', 'color' => '#3b82f6'],
        ['name' => 'Rekayasa Perangkat Lunak', 'code' => 'CS401', 'icon' => '🛠️', 'color' => '#ec4899'],
        ['name' => 'Sistem Operasi', 'code' => 'CS302', 'icon' => '🖥️', 'color' => '#84cc16'],
        ['name' => 'Machine Learning', 'code' => 'CS501', 'icon' => '🤖', 'color' => '#f97316'],
    ];

    private static int $index = 0;

    public function definition(): array
    {
        $subject = self::$subjects[self::$index % count(self::$subjects)];
        self::$index++;

        return [
            'name'       => $subject['name'],
            'slug'       => Str::slug($subject['name']) . '-' . Str::random(4),
            'code'       => $subject['code'],
            'description' => $this->faker->paragraph(2),
            'icon'       => $subject['icon'],
            'color'      => $subject['color'],
            'semester'   => 'Semester ' . $this->faker->numberBetween(1, 8),
            'created_by' => User::factory(),
            'is_active'  => true,
        ];
    }
}
