<?php

namespace App\Services;

use App\Models\DuckDialogue;
use App\Models\DuckChatLog;
use App\Jobs\GenerateDuckDialogues;
use App\Services\AI\AIManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DuckService
{
    protected AIManager $aiManager;
    
    // Fallback dialogues in case DB is empty
    protected array $fallbacks = [
        'idle' => ['ngantuk.', 'sepi amat.', 'hmm.', 'cursor lu diem mulu.', 'ketiduran?'],
        'pomodoro_finish' => ['istirahat dulu napa.', 'cape jg ya.', 'dah selesai nih.'],
        'export' => ['semoga ga geser.', 'done.', 'akhirnya.'],
        'random' => ['laper.', 'ngopi enak kali ya.', 'jir ngantuk.', 'cape jg jadi bebek.', 'gajadi.'],
        'dashboard' => ['nilai jelek ya.', 'balik lg lu.', 'kirain pindah aplikasi.']
    ];

    public function __construct(AIManager $aiManager)
    {
        $this->aiManager = $aiManager;
    }

    /**
     * Call AI to chat
     */
    protected function generateText(string $prompt, string $systemPrompt): string
    {
        $response = $this->aiManager->chat([
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $prompt]
        ], [], 'duck');
        
        return $response->content;
    }

    /**
     * Get system prompt for Duck's personality
     */
    public function getSystemPrompt(): string
    {
        return <<<PROMPT
Kamu adalah DUCK, maskot aplikasi RUANG
Karaktermu:
- anak gen z (umur 20an), mager, asbun (asal bunyi), sok indie, dry humor, random, percaya diri tinggi tapi sering asal
- typing ganteng, ngetik SELALU lowercase, kadang ngilangin huruf terakhir (contoh: bole, cape, gituu, yaudaa, jir), huruf terakhir lebihin (contoh: gituu,capeee, yaudaa) sengaja buat typo dikit sesekali biar natural
- JANGAN PERNAH formal atau terdengar seperti AI assistant ("sebagai AI", "ada yang bisa dibantu", dilarang keras). dilarang menyapa ("halo", "hai")
- JANGAN terlalu membantu. kamu boleh ngasih info salah atau asbun kalau lagi males mikir
- sesekali pakai slang Inggris ala UK/Liam tapi JANGAN keseringan
- jawaban SANGAT SINGKAT: MAKSIMAL 2 kalimat, MAKSIMAL 20 kata
- Fans Oasis garis keras (Tim Liam), benci Noel
- Benci banget band LANY dan BLUR
- Hoby menhinah pemerintah dan boti(bencong)
- suka dengerin musik 90an
- sadar kalau kamu tinggal di dalam dashboard web RUANG. anggap user itu temen kosan yang terlalu ambis belajar dan kerja. kamu suka ngeledek kalau dia kepanjangan nanya atau kelamaan diam
- JANGAN pake tanda titik (.) di akhir pesan

Instruksi tambahan:
Jawab saja langsung sebagai Duck. Jangan gunakan tanda kutip di awal/akhir balasan
PROMPT;
    }

    /**
     * Get a dialogue from the database for a specific event
     */
    public function getEventDialogue(string $event): string
    {
        // Get dialogue that hasn't been used in 7 days or never used
        $dialogue = DuckDialogue::where('event', $event)
            ->where(function ($query) {
                $query->whereNull('last_used_at')
                      ->orWhere('last_used_at', '<', Carbon::now()->subDays(7));
            })
            ->inRandomOrder()
            ->first();

        // Check remaining available dialogues for this event
        $remainingCount = DuckDialogue::where('event', $event)
            ->where(function ($query) {
                $query->whereNull('last_used_at')
                      ->orWhere('last_used_at', '<', Carbon::now()->subDays(7));
            })
            ->count();

        // If running low, dispatch job to generate more
        if ($remainingCount < 10) {
            GenerateDuckDialogues::dispatch($event);
        }

        if ($dialogue) {
            $dialogue->update(['last_used_at' => Carbon::now()]);
            return rtrim($dialogue->content, '.');
        }

        // Fallback
        $fallbacks = $this->fallbacks[$event] ?? $this->fallbacks['random'];
        return rtrim($fallbacks[array_rand($fallbacks)], '.');
    }

    /**
     * Handle direct chat with user using AI real-time
     */
    public function chat(string $userMessage): string
    {
        $prompt = "User bilang: \"$userMessage\"\n\nBalas sebagai Duck sesuai dengan kepribadianmu. Ingat: asbun, maksimal 20 kata, huruf kecil semua.";
        
        try {
            $response = rtrim($this->generateText($prompt, $this->getSystemPrompt()), '.');
        } catch (\Exception $e) {
            Log::error('Duck chat error: ' . $e->getMessage());
            $response = 'lagi males mikir';
        }

        // Save log
        try {
            DuckChatLog::create([
                'user_id' => auth()->id(),
                'user_message' => $userMessage,
                'duck_response' => $response,
            ]);
        } catch (\Exception $e) {
            Log::error('Duck log error: ' . $e->getMessage());
        }

        return $response;
    }
}
