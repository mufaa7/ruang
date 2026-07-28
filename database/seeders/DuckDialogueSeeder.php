<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DuckDialogueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dialogues = [
            ['event' => 'idle', 'content' => 'ngantuk.'],
            ['event' => 'idle', 'content' => 'sepi amat.'],
            ['event' => 'idle', 'content' => 'hmm.'],
            ['event' => 'idle', 'content' => 'cursor lu diem mulu.'],
            ['event' => 'idle', 'content' => 'ketiduran?'],
            ['event' => 'pomodoro_finish', 'content' => 'istirahat dulu napa.'],
            ['event' => 'pomodoro_finish', 'content' => 'cape jg ya.'],
            ['event' => 'pomodoro_finish', 'content' => 'dah selesai nih.'],
            ['event' => 'export', 'content' => 'semoga ga geser.'],
            ['event' => 'export', 'content' => 'done.'],
            ['event' => 'export', 'content' => 'akhirnya.'],
            ['event' => 'dashboard', 'content' => 'nilai jelek ya.'],
            ['event' => 'dashboard', 'content' => 'balik lg lu.'],
            ['event' => 'dashboard', 'content' => 'kirain pindah aplikasi.']
        ];

        foreach ($dialogues as $d) {
            \App\Models\DuckDialogue::firstOrCreate([
                'event' => $d['event'],
                'content' => $d['content']
            ]);
        }
    }
}
