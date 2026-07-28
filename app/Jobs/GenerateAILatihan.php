<?php

namespace App\Jobs;

use App\Models\Material;
use App\Models\Quiz;
use App\Models\FlashcardSet;
use App\Services\AILatihanService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class GenerateAILatihan implements ShouldQueue
{
    use Queueable;

    public $timeout = 120; // 2 minutes
    public $tries = 2;

    protected $materialId;
    protected $type;
    protected $subjectId;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $materialId, string $type, int $subjectId, int $userId)
    {
        $this->materialId = $materialId;
        $this->type = $type;
        $this->subjectId = $subjectId;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(AILatihanService $aiService): void
    {
        Log::info("Memulai generasi AI Latihan tipe {$this->type} untuk Material ID: {$this->materialId} oleh User ID: {$this->userId}");

        $material = Material::find($this->materialId);
        if (!$material) {
            Log::error("Material ID {$this->materialId} tidak ditemukan saat generasi AI Latihan.");
            return;
        }

        $result = $aiService->generate($material, $this->type);

        if (!$result) {
            Log::error("Gagal men-generate soal AI untuk Material ID: {$this->materialId}");
            return;
        }

        if ($this->type === 'kuis') {
            // Save as Quiz
            $quiz = Quiz::create([
                'user_id' => $this->userId,
                'subject_id' => $this->subjectId,
                'material_id' => $this->materialId,
                'title' => 'Latihan: ' . $material->title,
                'type' => 'latihan',
                'time_limit_minutes' => 15, // Default
            ]);

            foreach ($result as $q) {
                $type = (isset($q['type']) && $q['type'] === 'essay') ? 'essay' : 'multiple_choice';
                
                $quiz->questions()->create([
                    'type' => $type,
                    'question' => $q['question'] ?? 'Soal tidak terbaca',
                    'options' => isset($q['options']) ? $q['options'] : null,
                    'correct_answer' => $q['correct_answer'] ?? '',
                    'explanation' => $q['explanation'] ?? '',
                ]);
            }
        } elseif ($this->type === 'flashcard') {
            // Save as FlashcardSet
            $set = FlashcardSet::create([
                'user_id' => $this->userId,
                'subject_id' => $this->subjectId,
                'material_id' => $this->materialId,
                'title' => 'Flashcards: ' . $material->title,
            ]);

            foreach ($result as $f) {
                $set->flashcards()->create([
                    'front' => $f['front'] ?? 'Istilah',
                    'back' => $f['back'] ?? 'Definisi',
                ]);
            }
        }

        Log::info("Berhasil menyimpan Latihan AI tipe {$this->type} ke database.");
    }
}
