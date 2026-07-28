<?php

namespace App\Jobs;

use App\Services\AI\AIManager;
use App\Services\AI\Prompts\MakalahPromptService;
use App\Models\Makalah;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateMakalahJob implements ShouldQueue
{
    use Queueable;

    public int $tries   = 1;     // Jangan retry otomatis — hindari pemborosan token
    public int $timeout = 600;   // 10 menit (proses 15 sub-bab × ~30 detik)

    public function __construct(
        public Makalah $makalah,
        public string $title
    ) {}

    public function handle(AIManager $aiManager, MakalahPromptService $promptService): void
    {
        try {
            // ── FASE 1: Buat Outline ──────────────────────────────────────────
            $this->makalah->update([
                'ai_status'   => 'processing_outline',
                'ai_progress' => 'Menyusun kerangka (outline) makalah...',
            ]);

            if ($this->makalah->chapters()->count() === 0) {
                $messages = $promptService->buildOutlineMessages($this->title);
                $response = $aiManager->chat($messages, [
                    'temperature' => 0.3,
                    'max_tokens' => 1500,
                    'feature_name' => 'makalah_outline'
                ], 'makalah');
                $outline = $promptService->parseOutlineResponse($response);

                if (empty($outline)) {
                    throw new Exception('Gagal membuat kerangka makalah. AI mengembalikan data kosong.');
                }

                // Temuan #13: Wrap dalam transaction agar tidak ada data parsial
                DB::transaction(function () use ($outline) {
                    foreach ($outline as $index => $babData) {
                        $chapter = $this->makalah->chapters()->create([
                            'title'      => $babData['title']  ?? 'Bab ' . ($index + 1),
                            'bab_label'  => $babData['label']  ?? 'BAB ' . ($index + 1),
                            'type'       => $babData['type']   ?? 'bab',
                            'order'      => $index + 1,
                            'bab_number' => $index + 1,
                        ]);

                        foreach ($babData['subchapters'] ?? [] as $subIndex => $subData) {
                            $chapter->subchapters()->create([
                                'title'   => $subData['title'] ?? 'Sub-bab ' . ($subIndex + 1),
                                'content' => '',
                                'order'   => $subIndex + 1,
                            ]);
                        }
                    }
                });
            }

            // ── FASE 2: Isi Konten Sub-bab ───────────────────────────────────
            $this->makalah->update([
                'ai_status'   => 'processing_chapter',
                'ai_progress' => 'Kerangka selesai. Memulai penulisan konten...',
            ]);

            $chapters = $this->makalah
                ->chapters()
                ->with(['subchapters' => fn($q) => $q->orderBy('order')])
                ->orderBy('order')
                ->get();

            $completedSubchapters = []; // Konteks ringan antar sub-bab (Temuan #2)

            foreach ($chapters as $chapter) {
                foreach ($chapter->subchapters as $subchapter) {

                    // Temuan #15: Cek apakah user sudah cancel
                    $this->makalah->refresh();
                    if ($this->makalah->ai_status === 'cancelled') {
                        Log::info('GenerateMakalahJob dibatalkan user', ['makalah_id' => $this->makalah->id]);
                        return;
                    }

                    // Skip sub-bab yang sudah berisi konten (untuk resume)
                    if (!empty(trim($subchapter->content))) {
                        $completedSubchapters[] = $subchapter->title;
                        continue;
                    }

                    $this->makalah->update([
                        'ai_progress' => "{$chapter->bab_label}: Menulis '{$subchapter->title}'...",
                    ]);

                    $messages = $promptService->buildSubchapterMessages(
                        $this->title,
                        $chapter->title,
                        $subchapter->title,
                        $completedSubchapters
                    );

                    $response = $aiManager->chat($messages, [
                        'temperature' => 0.3,
                        'max_tokens' => 2000,
                        'feature_name' => 'makalah_chapter'
                    ], 'makalah');

                    $content = $promptService->parseSubchapterResponse($response);

                    $subchapter->update([
                        'content'      => $content,
                        'ai_generated' => true,
                    ]);
                    $completedSubchapters[] = $subchapter->title;
                }
            }

            // ── SELESAI ───────────────────────────────────────────────────────
            $this->makalah->update([
                'ai_status'   => 'completed',
                'ai_progress' => 'Makalah selesai dibuat!',
            ]);

        } catch (Exception $e) {
            // Temuan #4: Hapus dead code retry. Langsung fail dengan log yang informatif.
            Log::error('GenerateMakalahJob gagal', [
                'makalah_id' => $this->makalah->id,
                'title'      => $this->title,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);

            $this->makalah->update([
                'ai_status'   => 'failed',
                'ai_progress' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }
}
