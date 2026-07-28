<?php

namespace Database\Factories;

use App\Models\Paper;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaperSectionFactory extends Factory
{
    private static array $sectionTypes = [
        ['type' => 'introduction', 'title' => 'Pendahuluan'],
        ['type' => 'body', 'title' => 'Tinjauan Pustaka'],
        ['type' => 'body', 'title' => 'Metodologi Penelitian'],
        ['type' => 'body', 'title' => 'Hasil dan Pembahasan'],
        ['type' => 'conclusion', 'title' => 'Kesimpulan dan Saran'],
        ['type' => 'references', 'title' => 'Daftar Pustaka'],
    ];

    public function definition(): array
    {
        $section = $this->faker->randomElement(self::$sectionTypes);

        return [
            'paper_id' => Paper::factory(),
            'title'    => $section['title'],
            'content'  => $this->faker->paragraphs(rand(2, 5), true),
            'type'     => $section['type'],
            'order'    => $this->faker->numberBetween(0, 10),
            'snapshot' => null,
        ];
    }
}
