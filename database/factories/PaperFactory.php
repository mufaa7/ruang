<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaperFactory extends Factory
{
    private static array $titles = [
        'Analisis Algoritma Sorting pada Big Data',
        'Implementasi REST API dengan Laravel 11',
        'Dampak Media Sosial terhadap Produktivitas Mahasiswa',
        'Penerapan Machine Learning dalam Sistem Rekomendasi',
        'Optimasi Query Database dengan Indexing',
        'Studi Komparatif Framework JavaScript Modern',
        'Keamanan Aplikasi Web: SQL Injection Prevention',
        'Arsitektur Microservices vs Monolith',
        'Implementasi CI/CD Pipeline dengan GitHub Actions',
        'Analisis Sentimen menggunakan Natural Language Processing',
        'Pengembangan Mobile App dengan Flutter',
        'Sistem Manajemen Inventaris berbasis Web',
    ];

    private static int $index = 0;

    public function definition(): array
    {
        $title = self::$titles[self::$index % count(self::$titles)];
        self::$index++;

        return [
            'user_id'    => User::factory(),
            'subject_id' => Subject::factory(),
            'title'      => $title,
            'slug'       => Str::slug($title) . '-' . Str::random(5),
            'abstract'   => $this->faker->paragraph(3),
            'status'     => $this->faker->randomElement(['draft', 'published', 'published', 'published']),
            'visibility' => $this->faker->randomElement(['public', 'public', 'private', 'subject_only']),
            'metadata'   => ['keywords' => $this->faker->words(5)],
            'view_count' => $this->faker->numberBetween(0, 500),
            'download_count' => $this->faker->numberBetween(0, 100),
            'published_at' => $this->faker->optional(0.7)->dateTimeBetween('-6 months', 'now'),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attr) => [
            'status'       => 'published',
            'visibility'   => 'public',
            'published_at' => now()->subDays(rand(1, 90)),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attr) => [
            'status'     => 'draft',
            'visibility' => 'private',
        ]);
    }
}
