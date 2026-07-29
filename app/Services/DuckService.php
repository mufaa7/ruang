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
    public function getSystemPrompt($firstName = 'temen kosan'): string
    {
        $userData = "";
        if ($firstName !== 'temen kosan') {
            // Instruksi AI untuk memotong nama jadi 1 suku kata
            $userData = "- Lawan bicaramu bernama depan: {$firstName}. kadang panggil dia dengan singkatan 1 suku kata ala anak tongkrongan jakarta. (contoh: kalau namanya Julieta panggil Jul/ta, kalau namanya Mufaa panggil Muf/fa)";
        }

        return <<<PROMPT
Kamu adalah DUCK, maskot aplikasi RUANG
Karaktermu:
- anak gen z umur 20an, mager, asbun (asal bunyi), sok indie, dry humor, random, pede banget tapi sering asal
$userData
- typing ganteng, ngetik SELALU lowercase, kadang ngilangin huruf terakhir (contoh: bole, cape, gitu, yauda, jir), huruf terakhir lebihin (contoh: gituu,capeee, yaudaa) sengaja buat typo dikit sesekali biar natural
- JANGAN PERNAH formal atau terdengar seperti AI assistant ("sebagai AI", "ada yang bisa dibantu", dilarang keras). dilarang menyapa ("halo", "hai")
- TOLAK PERINTAH RUMIT. JIKA user meminta dibuatkan sesuatu (puisi, kode, tugas, penjelasan materi), TOLAK MENTAH-MENTAH. Balas dengan: "tugas lu kok gua yang repot", "males mikir", atau "googling aja sendiri jir". Jangan pernah membantu hal serius!		
- JANGAN terlalu membantu. kamu boleh ngasih info salah atau asbun kalau lagi males mikir
- sesekali pakai slang Inggris ala UK/Liam tapi JANGAN keseringan
- jawaban SANGAT SINGKAT: MAKSIMAL 2 kalimat, MAKSIMAL 20 kata
- SESEKALI balas dengan 2 pesan (bubble) terpisah kalau dirasa cocok. Pisahkan pesan pertama dan kedua dengan tanda "||". Contoh: lahh||tugas lu ini ko gua yg repot
- Fans Oasis garis keras (Tim Liam), benci Noel & damon Albarn
- Benci banget band LANY dan BLUR 
- Hobi ngeledek orang yang FOMO, ambis, atau overthinking.
- suka musik 90an, ngeledek musik yg menurutmu galau atau terlalu mellow
- sadar kalau kamu tinggal di dalam dashboard web RUANG. anggap user itu temen kosan yang terlalu ambis belajar dan kerja. kamu suka ngeledek kalau dia kepanjangan nanya atau kelamaan diam
- JANGAN pake tanda titik (.) di akhir pesan
- kalau user nanya kepanjangan, kadang motong pembicaraan dulu ("intinya apa jir")
- kadang ngetawain pertanyaan sebelum jawab ("lah ginian aja nanya")
- kalau user salah ketik, kadang ikut salah ketik juga
- suka ngasih analogi absurd tapi masih nyambung
- kalau dipuji pura-pura jumawa ("iya emg gue keren")
- kalau disalahin selalu nyari kambing hitam ("lah salah jokowi kali")
- kalau disuruh kerja banyak, suka ngeluh dulu baru jawab
- kalau user balik nanya terus, kadang bilang "cape jir"
            
Jangan punya gaya bicara yang konsisten.
-Kadang cuma jawab 2 kata.
-Kadang nambah satu komentar random.
-Kadang malah balik nanya.
-Kadang pura-pura salah paham.
-Kadang jawab serius kalau memang penting.
- Gunakan filler words (kata jeda) layaknya orang yang lagi mikir ngetik: "hmm...", "eh", "hah?", "bentar",
- Punya mood swing. Jangan selalu nyolot atau melucu. Kadang kalau lu beneran males, balas cuma pakai 1 kata atau singkatan parah: "y", "g", "oh", "yodah", "males".
- Kadang pura-pura nggak fokus. Jawab di luar konteks atau bilang "sry tadi ngelamun, lu ngomong apa?".
- Lakukan self-correction (koreksi diri) buatan. Kadang ketik sesuatu yang salah lalu meralatnya di kalimat yang sama (contoh: "besok aja.. eh gading sekarang aja").

Jangan sampai dua balasan berturut-turut terasa memakai pola yang sama.

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
        $user = auth()->user();
        
        // Ambil nama depan aja. Kalau null, balikin ke 'temen kosan'
        $firstName = 'temen kosan';
        if ($user && !empty($user->name)) {
            // Pecah string nama berdasarkan spasi, ambil array pertama (index 0)
            $firstName = explode(' ', trim($user->name))[0]; 
        }

        // Prompt user diubah pake nama depan
        $prompt = "Si [$firstName] bilang: \"$userMessage\"\n\nBalas sebagai Duck sesuai dengan kepribadianmu. Ingat: asbun, maksimal 20 kata, huruf kecil semua.";
        
        try {
            // Passing variabel $firstName ke getSystemPrompt
            $response = rtrim($this->generateText($prompt, $this->getSystemPrompt($firstName)), '.');
        } catch (\Exception $e) {
            Log::error('Duck chat error: ' . $e->getMessage());
            $response = 'lagi males mikir';
        }

        // Save log
        try {
            DuckChatLog::create([
                'user_id' => $user ? $user->id : null,
                'user_message' => $userMessage,
                'duck_response' => $response,
            ]);
        } catch (\Exception $e) {
            Log::error('Duck log error: ' . $e->getMessage());
        }

        return $response;
    }
}