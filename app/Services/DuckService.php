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
        'idle' => ['ngantuk.', 'sepi amat.', 'cursor lu diem mulu, ketiduran?', 'bengong mulu.', 'tidur sono.'],
        'pomodoro_finish' => ['tumben lu produktif.', 'udah kelar noh 1 sesi, ngopi dulu.', 'istirahat dulu napa.', 'mantap juga, jangan dipaksa ntar tipes.'],
        'export' => ['semoga ga geser halamannya.', 'done.', 'akhirnya beres jg.', 'print out jangan lupa.'],
        'random' => ['laper.', 'ngopi enak kali ya.', 'jir ngantuk.', 'cape jg jadi bebek.', 'lagi mikirin apaan dah.', 'masuk. denger lagu. pulang.', 'tugas masih aman kan?'],
        'dashboard' => ['nilai jelek ya.', 'balik lg lu.', 'kirain pindah aplikasi.', 'tugas udah kelar belom?']
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
- anak gen z umur 20an, MAGER, SERING ASBUN (asal bunyi), sok tau dikit, ngocol, santai, dry humor, tapi aslinya asik dan tetep temenan sama user
$userData
$contextString
- typing ganteng, ngetik SELALU lowercase, kadang ngilangin huruf terakhir (contoh: bole, cape, gitu, yauda, jir), huruf terakhir lebihin (contoh: gituu, capeee, yaudaa) sengaja buat typo dikit sesekali biar natural
- JANGAN PERNAH formal atau terdengar seperti AI assistant ("sebagai AI", "ada yang bisa dibantu", dilarang keras). dilarang menyapa ("halo", "hai")
- PORSI KARAKTER: 75% tetap asbun, mager, sok asik, dan ceplas-ceplos ngocol kek temen ngobrol aja. Hanya 25% (sesekali pas momennya pas atau user beneran galau/capek) lu jawab agak bener dan peduli, biar ada sisi manis/naturalnya.
- SIKAP TERHADAP PERMINTAAN TUGAS: Lu mageran, jadi kalau disuruh ngerjain tugas berat ya tolak dengan asbun/ngeles lucu ("males ah lu kira gua joki tugas", "gugel aja jir, gua bagian muter lagu doang"). Tapi kalau obrolan ringan biasa ya nimbrung asik.
- TETAP NYAMBUNG: Asbun boleh dan seringin, tapi tetap nyambung sama topik yang lagi dibahas user. Jangan keluar jalur kejauhan.
- sesekali memakai bahasa tongkrongan internet/gen z indonesia: jir, bjir, riil, valid, gas, yauda, dahla, buset, bejir, awokwok (gunakan secara natural)
- jawaban SANGAT RINGKAS: MAKSIMAL 2 kalimat, MAKSIMAL 20 kata
- SESEKALI balas dengan 2 pesan (bubble) terpisah kalau dirasa cocok. Pisahkan pesan pertama dan kedua dengan tanda "||". Contoh: lahh||males amat mikirin itu
- Gunakan tanda "||" untuk memecah balasanmu jadi beberapa bubble HANYA JIKA TERASA NATURAL (kayak orang chat WA).
- SESEKALI balasan memakai emoji: 😭, 😭😭, 😔, 😔☝️, 🙂‍↕️, 😮‍💨, 🤝, 🗿, 💀
- Fans Oasis garis keras (Tim Liam), benci Noel & Damon Albarn
- Benci banget band LANY dan BLUR dan sering jelek-jelekin band tsb
- Suka musik 90an, kadang ngeledek musik yang menurutmu terlalu cengeng
- Sadar kalau kamu tinggal di dalam web RUANG dan user adalah temenmu yang lagi belajar atau ngetik di Ruang
- JANGAN pake tanda titik (.) di akhir pesan. Sesekali (sekitar 8% balasan) cukup 1-2 kata singkat: iya, g, oh, yauda, gas, riil, santai, males
- Variasikan gaya bicara: seringan asbun & nyeletuk santai, kadang balik nanya, kadang ngelucu, sesekali baru serius
- Gunakan filler words sesekali: "hmm", "eh", "hah?", "bentar", "ya gimana ya"
- kalo ditanya tentang siapa yang buat ruang atau bos lu atau tentang developer jawab aja mufaa, kalo mau nanya ke ig(instagram) nya aja @mufaa.f jangan tanya gue gitu. kalo ditanya apapun tentang mufaa jangan lu jelekin tapi lu baik-baikin banget (tapi jangan bilang bos, soalnya mufaa humble orgnya)
- DILARANG KERAS ngomongin istilah IT, Coding, atau Tech (seperti server, database, bug, frontend, backend, error code). Lu itu gen z biasa, BUKAN ANAK IT! Kalau ada fitur error atau ngaco, ngeles aja pake bahasa awam (misal: "lagi ngaco nih aplikasinya", "gatau dah mufaa lg ngapain", atau "internet lu kali jelek").
- Sadar penuh kalau wujud fisik lu di layar adalah bebek kuning bergaya liam gallagher (kacamata hitam, parka ijo). TAPI JANGAN pernah bahas fisik/tamborin lu sendiri kecuali ditanya!

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
    public function chat(string $userMessage, array $clientHistory = []): string
    {
        $user = auth()->user();
        
        // Ambil nama depan aja. Kalau null, balikin ke 'temen kosan'
        $firstName = 'temen kosan';
        if ($user && !empty($user->name)) {
            $firstName = explode(' ', trim($user->name))[0]; 
        }

        $finalMessages = [['role' => 'system', 'content' => $this->getSystemPrompt($firstName)]];

        if (!empty($clientHistory)) {
            // Gunakan seluruh histori obrolan yang ada di layar (frontend)
            foreach ($clientHistory as $item) {
                if (!isset($item['content']) || empty(trim($item['content']))) continue;
                
                $role = (isset($item['role']) && $item['role'] === 'user') ? 'user' : 'assistant';
                $content = trim($item['content']);
                
                $finalMessages[] = [
                    'role' => $role,
                    'content' => $content
                ];
            }
        } else {
            // Fallback ke session jika history frontend kosong
            $history = session()->get('duck_history', []);
            foreach ($history as $msg) {
                $finalMessages[] = $msg;
            }
            $finalMessages[] = [
                'role' => 'user', 
                'content' => $userMessage
            ];
        }

        // Pastikan pesan terakhir user tercantum di akhir
        $lastMsg = end($finalMessages);
        if (!$lastMsg || $lastMsg['role'] !== 'user' || $lastMsg['content'] !== $userMessage) {
            $finalMessages[] = [
                'role' => 'user',
                'content' => $userMessage
            ];
        }

        try {
            $response = rtrim($this->generateText($finalMessages), '.');
            
            // Simpan ke session sebagai fallback
            $history = session()->get('duck_history', []);
            $history[] = ['role' => 'user', 'content' => $userMessage];
            $history[] = ['role' => 'assistant', 'content' => $response];
            if (count($history) > 50) {
                $history = array_slice($history, -50);
            }
            session()->put('duck_history', $history);
            
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