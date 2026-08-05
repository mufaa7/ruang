<?php

namespace App\Http\Controllers;

use App\Services\AI\AIManager;
use App\Services\AI\Prompts\MakalahPromptService;
use App\Jobs\GenerateMakalahJob;
use App\Models\Makalah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiCopilotController extends Controller
{
    public function __construct(
        private readonly AIManager $aiManager,
        private readonly MakalahPromptService $promptService
    ) {}

    // ── Autocomplete ─────────────────────────────────────────────────────────

    public function autocomplete(Request $request): JsonResponse
    {
        $request->validate([
            'context'      => 'nullable|string|max:2000',
            'current_text' => 'required|string|max:1000',
        ]);

        $messages = $this->promptService->buildAutocompleteMessages(
            $request->input('context', ''),
            $request->input('current_text')
        );

        $response = $this->aiManager->chat($messages, [
            'temperature' => 0.6,
            'max_tokens' => 150,
            'feature_name' => 'makalah_autocomplete'
        ], 'makalah');

        $suggestion = $this->promptService->parseAutocompleteResponse($response);

        return response()->json([
            'success'    => $suggestion !== null,
            'suggestion' => $suggestion
        ]);
    }

    // ── Referensi ────────────────────────────────────────────────────────────

    public function generateReferences(Request $request, Makalah $makalah): JsonResponse
    {
        $this->authorize('update', $makalah);
        $request->validate([
            'topic' => 'required|string|max:255',
        ]);

        $messages = $this->promptService->buildReferencesMessages($request->topic);
        $response = $this->aiManager->chat($messages, [
            'temperature' => 0.4,
            'max_tokens' => 800,
            'feature_name' => 'makalah_references'
        ], 'makalah');
        
        $refs = $this->promptService->parseReferencesResponse($response);

        if (empty($refs)) {
            return response()->json(['success' => false, 'message' => 'Gagal generate referensi dari AI.']);
        }

        $createdRefs = [];
        foreach ($refs as $refData) {
            $createdRefs[] = $makalah->references()->create(
                array_merge($refData, ['user_id' => auth()->id()])
            );
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Berhasil generate ' . count($createdRefs) . ' referensi baru!',
            'references' => $createdRefs
        ]);
    }

    // ── Status & Progres ─────────────────────────────────────────────────────

    public function checkAiStatus(Makalah $makalah): JsonResponse
    {
        $this->authorize('view', $makalah);

        // Hitung progress sub-bab untuk progress bar
        $total = 0;
        $done  = 0;
        if (in_array($makalah->ai_status, ['processing_chapter', 'completed'])) {
            $subchapters = $makalah->chapters()->with('subchapters')->get()->flatMap->subchapters;
            $total = $subchapters->count();
            $done  = $subchapters->filter(fn($s) => !empty(trim($s->content)))->count();
        }

        return response()->json([
            'ai_status'            => $makalah->ai_status   ?? 'idle',
            'ai_progress'          => $makalah->ai_progress  ?? '',
            'total_subchapters'    => $total,
            'completed_subchapters'=> $done,
            'percentage'           => $total > 0 ? (int) round($done / $total * 100) : 0,
        ]);
    }

    public function getChaptersHtml(Makalah $makalah)
    {
        $this->authorize('view', $makalah);
        $makalah->load(['chapters.subchapters']);

        return view('makalah.components.chapter', compact('makalah'))->render();
    }

    /**
     * Regenerate konten satu sub-bab tertentu dengan AI.
     */
    public function regenerateSubchapter(Request $request, Makalah $makalah, \App\Models\MakalahSubchapter $subchapter): JsonResponse
    {
        $this->authorize('generateAi', $makalah);

        // Pastikan sub-bab milik makalah ini
        if ($subchapter->chapter->makalah_id !== $makalah->id) {
            abort(403);
        }

        $chapter = $subchapter->chapter;
        $messages = $this->promptService->buildSubchapterMessages(
            $makalah->judul,
            $chapter->title,
            $subchapter->title
        );

        $response = $this->aiManager->chat($messages, [
            'temperature' => 0.3,
            'max_tokens' => 2000,
            'feature_name' => 'makalah_chapter'
        ], 'makalah');

        $content = $this->promptService->parseSubchapterResponse($response);

        if (empty($content)) {
            return response()->json(['success' => false, 'message' => 'AI gagal menghasilkan konten. Coba lagi.']);
        }

        $subchapter->update([
            'content'      => $content,
            'ai_generated' => true,
        ]);

        return response()->json([
            'success' => true,
            'content' => $content,
        ]);
    }

    // ── Generate Full Makalah ────────────────────────────────────────────────

    public function generateFullMakalah(Request $request, Makalah $makalah): JsonResponse
    {
        $this->authorize('generateAi', $makalah);

        // Temuan #8: Validasi + sanitasi input (mencegah prompt injection)
        $request->validate([
            'title'  => ['required', 'string', 'max:255', 'regex:/^[\pL\pN\s\(\)\-\.\,\:\!\?]+$/u'],
            'resume' => 'nullable|boolean',
            'prompt' => 'nullable|string|max:1000',
        ]);

        // Temuan #9: Cek status — tolak jika masih berjalan (anti double-submit)
        if (in_array($makalah->ai_status, ['queued', 'processing_outline', 'processing_chapter'])) {
            return response()->json([
                'success' => false,
                'message' => 'Proses AI masih berjalan. Silakan tunggu atau batalkan terlebih dahulu.',
            ]);
        }

        $resume = filter_var($request->input('resume', false), FILTER_VALIDATE_BOOLEAN);

        $makalah->update([
            'ai_status'   => 'queued',
            'ai_progress' => 'Menunggu antrean untuk membuat kerangka makalah...',
        ]);

        // Hapus bab lama hanya jika tidak resume
        if (!$resume && $makalah->chapters()->exists()) {
            $makalah->chapters()->delete();
        }

        GenerateMakalahJob::dispatch($makalah, $request->input('title'));

        // Hitung estimasi waktu untuk UX (Temuan #14)
        $subchapterCount = $resume
            ? $makalah->chapters()->with('subchapters')->get()->flatMap->subchapters->filter(fn($s) => empty(trim($s->content)))->count()
            : 15; // default estimate

        $estimasiMenit = max(1, (int) ceil($subchapterCount * 25 / 60));

        return response()->json([
            'success'          => true,
            'message'          => 'Proses generate makalah telah dimasukkan ke antrean.',
            'estimasi_menit'   => $estimasiMenit,
            'subchapter_count' => $subchapterCount,
        ]);
    }

    // ── Cancel Generate ──────────────────────────────────────────────────────

    /**
     * Temuan #15: User bisa membatalkan proses AI yang sedang berjalan.
     */
    public function cancelGenerate(Makalah $makalah): JsonResponse
    {
        $this->authorize('update', $makalah);

        if (!in_array($makalah->ai_status, ['queued', 'processing_outline', 'processing_chapter'])) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada proses AI yang sedang berjalan.',
            ]);
        }

        $makalah->update([
            'ai_status'   => 'cancelled',
            'ai_progress' => 'Dibatalkan oleh pengguna.',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Proses AI berhasil dibatalkan.',
        ]);
    }
}
