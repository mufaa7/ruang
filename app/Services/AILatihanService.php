<?php

namespace App\Services;

use App\Models\Material;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AILatihanService
{
    public function generate(Material $material, string $type): ?array
    {
        $content = substr($material->content ?? $material->preview, 0, 10000);

        if ($type === 'kuis') {
            $promptInstruction = "Anda adalah dosen pembuat soal jenius. Berdasarkan teks materi berikut, buatlah soal kuis campuran: 5 soal Pilihan Ganda (A-E) dan 3 soal Essay Singkat.\n\n"
                . "KEMBALIKAN HANYA ARRAY JSON VALID SEPERTI CONTOH BERIKUT (tanpa markdown):\n"
                . "[\n"
                . "  {\n"
                . "    \"type\": \"pg\",\n"
                . "    \"question\": \"Pertanyaan pilihan ganda?\",\n"
                . "    \"options\": {\"A\": \"Jawaban A\", \"B\": \"Jawaban B\", \"C\": \"Jawaban C\", \"D\": \"Jawaban D\", \"E\": \"Jawaban E\"},\n"
                . "    \"correct_answer\": \"C\",\n"
                . "    \"explanation\": \"Penjelasan singkat kenapa C benar.\"\n"
                . "  },\n"
                . "  {\n"
                . "    \"type\": \"essay\",\n"
                . "    \"question\": \"Pertanyaan essay?\",\n"
                . "    \"options\": null,\n"
                . "    \"correct_answer\": \"Jawaban ekspektasi yang benar secara singkat.\",\n"
                . "    \"explanation\": \"Penjelasan detail cara menjawabnya.\"\n"
                . "  }\n"
                . "]\n\n"
                . "Materi:\n" . $content;
        } else {
            $promptInstruction = "Anda adalah pembuat flashcard yang efektif. Berdasarkan teks materi berikut, buatlah 10 flashcard penting untuk dihafal.\n\n"
                . "KEMBALIKAN HANYA ARRAY JSON VALID SEPERTI CONTOH BERIKUT (tanpa markdown):\n"
                . "[\n"
                . "  {\n"
                . "    \"front\": \"Istilah atau Pertanyaan (singkat)\",\n"
                . "    \"back\": \"Definisi atau Jawaban (singkat dan jelas)\"\n"
                . "  }\n"
                . "]\n\n"
                . "Materi:\n" . $content;
        }

        $messages = [
            [
                'role' => 'user',
                'content' => $promptInstruction
            ]
        ];

        try {
            $aiManager = app(\App\Services\AI\AIManager::class);
            $response = $aiManager->chat($messages, [
                'temperature' => 0.7,
                'max_tokens' => 2000
            ], 'quiz');

            // Strip markdown JSON codeblocks if any
            $cleanResponse = preg_replace('/```json\s*(.*?)\s*```/s', '$1', $response);
            $cleanResponse = preg_replace('/```\s*(.*?)\s*```/s', '$1', $cleanResponse);
            $cleanResponse = trim($cleanResponse);
            
            $data = json_decode($cleanResponse, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $data;
            }

            Log::error('AI Latihan invalid JSON response', ['response' => $response]);
            return null;
        } catch (\Exception $e) {
            Log::error('Exception saat Generate Latihan AI: ' . $e->getMessage());
            return null;
        }
    }
}
