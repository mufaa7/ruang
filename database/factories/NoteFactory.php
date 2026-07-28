<?php

namespace Database\Factories;

use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    private static array $colors = [
        '#fef3c7', '#dbeafe', '#dcfce7', '#fce7f3',
        '#ede9fe', '#fee2e2', '#e0f2fe', '#f0fdf4',
    ];

    private static array $titles = [
        'Ringkasan Materi Algoritma Greedy',
        'Catatan Kuliah Basis Data - Normalisasi',
        'To-Do List Tugas Akhir',
        'Ide Proyek Aplikasi Mobile',
        'Summary Buku Clean Code',
        'Notes Tentang Design Pattern',
        'Rumus-Rumus Kalkulus Penting',
        'Vocab Baru Bahasa Inggris',
        'Brainstorming Topik Skripsi',
        'Review Paper ML Terbaru',
        'Rencana Belajar Bulan Ini',
        'Insight dari Workshop UI/UX',
    ];

    private static int $index = 0;

    public function definition(): array
    {
        $title = self::$titles[self::$index % count(self::$titles)];
        self::$index++;

        return [
            'user_id'        => User::factory(),
            'folder_id'      => null,
            'subject_id'     => null,
            'title'          => $title,
            'content'        => '<p>' . implode('</p><p>', $this->faker->paragraphs(rand(1, 4))) . '</p>',
            'color'          => $this->faker->randomElement(self::$colors),
            'is_pinned'      => $this->faker->boolean(15),
            'visibility'     => $this->faker->randomElement(['private', 'private', 'private', 'public']),
            'last_edited_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }

    public function pinned(): static
    {
        return $this->state(fn (array $attr) => ['is_pinned' => true]);
    }
}
