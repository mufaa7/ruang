<?php

namespace App\Jobs;

use App\Models\Material;
use App\Models\Note;
use App\Services\AISummarizerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class GenerateAISummary implements ShouldQueue
{
    use Queueable;

    public $timeout = 120; // 2 minutes

    public function __construct(
        public int $userId,
        public int $subjectId,
        public ?Material $material = null,
        public ?string $manualText = null
    ) {}

    public function handle(AISummarizerService $aiService): void
    {
        Log::info("Memulai generasi AI summary untuk Subject ID: {$this->subjectId}");

        $result = null;
        if ($this->material) {
            $result = $aiService->summarizeMaterial($this->material);
        } elseif ($this->manualText) {
            $result = $aiService->summarizeText($this->manualText);
        }

        if ($result) {
            $note = Note::create([
                'user_id' => $this->userId,
                'subject_id' => $this->subjectId,
                'material_id' => $this->material?->id,
                'title' => '✨ AI: ' . ($result['title'] ?? 'Ringkasan Materi'),
                'content' => $result['content'] ?? 'Tidak ada ringkasan yang dihasilkan.',
                'color' => '#f0fdf4', // Warna khusus untuk AI
                'is_ai_generated' => true,
                'visibility' => 'private',
            ]);

            if (isset($result['keywords']) && is_array($result['keywords'])) {
                foreach ($result['keywords'] as $keyword) {
                    $tag = \App\Models\Tag::firstOrCreate(
                        ['name' => $keyword],
                        ['slug' => \Illuminate\Support\Str::slug($keyword)]
                    );
                    $note->tags()->attach($tag->id);
                }
            }

            Log::info("Berhasil membuat AI Note ID: {$note->id}");
        } else {
            Log::error("Gagal membuat AI summary untuk Subject ID: {$this->subjectId}");
        }
    }
}
