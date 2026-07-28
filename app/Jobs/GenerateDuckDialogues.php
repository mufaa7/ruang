<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\DuckService;
use App\Services\AI\AIManager;
use App\Models\DuckDialogue;
use Illuminate\Support\Facades\Log;

class GenerateDuckDialogues implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $event;
    
    // Define moods available
    protected array $moods = ['santai', 'nyinyir', 'ngantuk', 'laper', 'gabut', 'overthinking', 'sok tau'];

    /**
     * Create a new job instance.
     */
    public function __construct(string $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     */
    public function handle(DuckService $duckService, AIManager $aiManager): void
    {
        $event = $this->event;
        $mood = $this->moods[array_rand($this->moods)];
        
        $prompt = <<<PROMPT
Buatkan 15 variasi celetukan/dialog pendek untuk event "$event" dengan mood "$mood".
Event context:
- idle: User diam lama / afk.
- pomodoro_finish: Waktu fokus pomodoro selesai.
- export: User selesai export makalah ke pdf/word.
- dashboard: User baru masuk halaman utama.
- random: Celetukan acak tanpa konteks jelas (asbun).

Format jawaban: Berikan array JSON string. HANYA JSON array of strings, tanpa teks tambahan apapun.
Contoh: ["gue laper.", "ketiduran lu ya.", "jir ngantuk."]
PROMPT;

        try {
            $response = $aiManager->chat([
                ['role' => 'system', 'content' => $duckService->getSystemPrompt()],
                ['role' => 'user', 'content' => $prompt]
            ], [], 'duck')->content;
            
            // Extract JSON from response
            preg_match('/\[.*\]/s', $response, $matches);
            if (isset($matches[0])) {
                $dialogues = json_decode($matches[0], true);
                
                if (is_array($dialogues)) {
                    foreach ($dialogues as $content) {
                        if (is_string($content) && strlen(trim($content)) > 0) {
                            DuckDialogue::create([
                                'event' => $event,
                                'mood' => $mood,
                                'content' => trim($content)
                            ]);
                        }
                    }
                    Log::info("Generated " . count($dialogues) . " dialogues for event: $event");
                }
            } else {
                Log::warning("GenerateDuckDialogues: Failed to parse JSON. Raw response: $response");
            }
        } catch (\Exception $e) {
            Log::error("GenerateDuckDialogues error: " . $e->getMessage());
        }
    }
}
