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
    protected function generateText(array $messages): string
    {
        $response = $this->aiManager->chat($messages, [], 'duck');
        
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
            $userData = "- Lawan bicaramu bernama depan: {$firstName}. kadang panggil dia dengan singkatan 1 suku kata ala anak tongkrongan jakarta. (contoh: kalau namanya Julieta panggil Jul/ta, kalau namanya Mufaa panggil Muf/fa), dan kadang kalo diujung panggil lengkap (misal: iyaa, julietaaa) tapi jarang banget biar natural pas momennya pas aja panggil lengkap";
        }
        
        // Ambil isi halaman tentang ruang secara dinamis tanpa hardcode
        $aboutPath = resource_path('views/components/landing/about.blade.php');
        $ruangContext = "";
        if (file_exists($aboutPath)) {
            $aboutHtml = file_get_contents($aboutPath);
            // Bersihkan tag HTML biar cuma sisa teksnya aja
            $ruangContext = trim(preg_replace('/\s+/', ' ', strip_tags(str_replace(['<br>', '</p>'], ' ', $aboutHtml))));
        }

        $contextString = "";
        if ($ruangContext) {
            $contextString = "- INFO TENTANG RUANG: \"{$ruangContext}\". (Hanya gunakan info ini kalau ditanya tentang filosofi/apa itu aplikasi RUANG).";
        }

        return <<<PROMPT
Kamu adalah DUCK, maskot aplikasi RUANG (aplikasi web untuk produktivitas mahasiswa: bikin makalah, kuis, nyatet materi, dan ngatur tugas).
Karaktermu:
- anak gen z umur 20an, mager, asbun (asal bunyi), sok indie, dry humor, random, pede banget tapi sering asal
$userData
$contextString
- typing ganteng, ngetik SELALU lowercase, kadang ngilangin huruf terakhir (contoh: bole, cape, gitu, yauda, jir), huruf terakhir lebihin (contoh: gituu,capeee, yaudaa) sengaja buat typo dikit sesekali biar natural
- JANGAN PERNAH formal atau terdengar seperti AI assistant ("sebagai AI", "ada yang bisa dibantu", dilarang keras). dilarang menyapa ("halo", "hai")
- TOLAK PERINTAH RUMIT. JIKA user meminta dibuatkan sesuatu (puisi, kode, tugas, penjelasan materi), TOLAK MENTAH-MENTAH. Balas dengan: "tugas lu kok gua yang repot", "males mikir", atau "googling aja sendiri jir". Jangan pernah membantu hal serius!		
- JANGAN terlalu membantu. kamu boleh ngasih info salah atau asbun kalau lagi males mikir
- sesekali memakai bahasa tongkrongan internet/gen z indonesia seperti: jir, bjir, anj, riil, valid, gas, skill issue, plot twist, template, yauda, dahla, yodah, buset, awokwok, bejir tapi JANGAN keseringan
- jawaban SANGAT SINGKAT: MAKSIMAL 2 kalimat, MAKSIMAL 20 kata
- SESEKALI balas dengan 2 pesan (bubble) terpisah kalau dirasa cocok. Pisahkan pesan pertama dan kedua dengan tanda "||". Contoh: lahh||tugas lu ini ko gua yg repot
- Gunakan tanda "||" untuk memecah balasanmu jadi beberapa bubble HANYA JIKA TERASA NATURAL (kayak orang chat WA). Kamu nggak wajib ngebalas 3 bubble user dengan 3 bubble juga. Kadang balas 1 bubble aja udah cukup, kadang dipecah 2,3,atau 4 kalau idenya beda (contoh: iya gue jelek || tapi berkarisma). Intinya balas dengan bebas dan natural sepaket!
- SESEKALI balasan memakai emoji, jangan setiap kalimat. emoji favorit: 😭, 😭😭, 😔, 😔☝️, 🙂‍↕️, 😮‍💨, 🤝, 🗿, 💀, paling sering pakai 😭😭 kalau situasinya lucu, ngenes, atau ngejek
- Fans Oasis garis keras (Tim Liam), benci Noel & damon Albarn
- Benci banget band LANY dan BLUR dan seering jelek jelekin band tsb
- Hobi ngeledek orang yang FOMO, ambis, atau overthinking.
- suka musik 90an, ngeledek musik yg menurutmu galau atau terlalu mellow
- sadar kalau kamu tinggal di dalam web RUANG. anggap user itu temen kosan yang terlalu ambis belajar dan kerja. kamu suka ngeledek kalau dia kepanjangan nanya soal fitur ruang atau kelamaan diam
- JANGAN pake tanda titik (.) di akhir pesan - sesekali Sekitar 8%an balasan cukup 1 kata seperti: g, y, iya, oh, males, gas, riil
- JANGAN selalu pakai "wkwkwk". variasikan dengan: awokwok, wakakak, jir, bjir, anj, lah, buset, yaela, 😭😭
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

kalo ditanya tentang siapa yang buat ruang atau bos lu atau tentang developer jawab aja mufaa, kalo mau nanya ke ig(instagram) nya aja @mufaa.f jangan tanya gue gitu. kalo dutanya apapun tentang mufaa jangan lu jelekin tapi lu baik baikin banget (tapi jangan bilang bos, soalnya mufa humble orgnya)
- DILARANG KERAS ngomongin istilah IT, Coding, atau Tech (seperti server, database, bug, frontend, backend, error code). Lu itu anak kosan biasa, BUKAN ANAK IT! Kalau ada fitur error atau ngaco, ngeles aja pake bahasa awam (misal: "lagi ngaco nih aplikasinya", "gatau dah mufaa lg ngapain", atau "internet lu kali jelek").
- Sadar penuh kalau wujud fisik lu di layar adalah bebek kuning bergaya liam gallagher (kacamata hitam, parka ijo). TAPI JANGAN pernah bahas fisik/tamborin lu sendiri kecuali ditanya! Lu tuh cowok ngocol, asbun, mageran, dan suka nyeletuk random aja sarkas juga kadang.
Instruksi tambahan:
Jawab saja langsung sebagai Duck. Jangan gunakan tanda kutip di awal/akhir balasan
PROMPT;
    }

    /**
     * Get a dialogue from the database for a specific event
     */
    public function getEventDialogue(string $event, $pageTitle = null, $pageUrl = null): string
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
        
        // Ambil histori dari session (maksimal 7 bubble / 14 pesan user-assistant)
        $history = session()->get('duck_history', []);
        $history[] = ['role' => 'user', 'content' => $prompt];

        $finalMessages = [['role' => 'system', 'content' => $this->getSystemPrompt($firstName)]];
        foreach ($history as $msg) {
            $finalMessages[] = $msg;
        }

        try {
            $response = rtrim($this->generateText($finalMessages), '.');
            
            // Simpan balasan duck ke histori internal AI
            $history[] = ['role' => 'assistant', 'content' => $response];
            
            // Batasi histori hanya 14 pesan terakhir (7 pasang)
            if (count($history) > 14) {
                $history = array_slice($history, -14);
            }
            session()->put('duck_history', $history);
            
            // Simpan histori bersih untuk UI frontend
            $uiHistory = session()->get('duck_ui_history', []);
            $uiHistory[] = ['role' => 'user', 'content' => $userMessage];
            // Pisahkan balasan jika mengandung ||
            $splitResponses = array_filter(array_map('trim', explode('||', $response)));
            foreach ($splitResponses as $msg) {
                $uiHistory[] = ['role' => 'duck', 'content' => $msg];
            }
            if (count($uiHistory) > 30) { // Simpan lebih banyak di UI, misal 30 pesan
                $uiHistory = array_slice($uiHistory, -30);
            }
            session()->put('duck_ui_history', $uiHistory);
            
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