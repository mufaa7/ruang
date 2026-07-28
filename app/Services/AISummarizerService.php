<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Str;

class AISummarizerService
{
    /**
     * Rangkum materi kuliah (teks atau PDF) menggunakan Gemini AI.
     *
     * @param \App\Models\Material $material
     * @return array|null
     */
    public function summarizeMaterial($material): ?array
    {
        $text = $this->extractText($material);

        if (empty($text)) {
            Log::warning("Materi ID {$material->id} tidak memiliki teks untuk dirangkum.");
            return null;
        }

        return $this->summarizeText($text);
    }

    /**
     * Rangkum teks manual menggunakan Gemini AI.
     *
     * @param string $text
     * @return array|null
     */
    public function summarizeText(string $text): ?array
    {
        if (empty($text)) {
            return null;
        }

        // Batasi teks agar tidak melebihi token limit (opsional, Gemini 1.5/2.5 punya konteks besar, tapi kita potong max 100,000 karakter)
        $text = Str::limit($text, 100000, '... [teks terpotong karena terlalu panjang]');

        $prompt = <<<EOT
Anda adalah asisten akademik cerdas untuk mahasiswa. Rangkum teks materi perkuliahan di bawah ini.
Berikan hasil dalam format JSON yang valid TANPA markdown formatting (tanpa ```json ... ```) dengan struktur berikut:
{
  "title": "Judul Rangkuman (Maks 10 kata, ada emoji)",
  "content": "Rangkuman komprehensif dalam bentuk markdown dengan poin-poin penting, bullet points, dan mudah dibaca. Tidak perlu menyertakan judul di dalam konten ini karena akan ditampilkan terpisah.",
  "keywords": ["Kata Kunci 1", "Kata Kunci 2", "Kata Kunci 3"]
}

Teks Materi:
---
{$text}
---
EOT;

        return $this->callAI($prompt);
    }

    private function extractText($material): string
    {
        // Jika materi berupa teks langsung
        if ($material->file_type === 'text') {
            return $material->content ?? '';
        }

        // Jika materi berupa file PDF
        if ($material->file_type === 'pdf' && $material->file_path) {
            try {
                $path = storage_path('app/public/' . $material->file_path);
                if (file_exists($path)) {
                    $parser = new Parser();
                    $pdf = $parser->parseFile($path);
                    return $pdf->getText();
                }
            } catch (Exception $e) {
                Log::error("Gagal mengekstrak teks dari PDF materi ID {$material->id}: " . $e->getMessage());
            }
        }

        return '';
    }

    private function callAI(string $prompt): ?array
    {
        $messages = [
            [
                'role' => 'user',
                'content' => $prompt
            ]
        ];

        try {
            $aiManager = app(\App\Services\AI\AIManager::class);
            $response = $aiManager->chat($messages, [
                'temperature' => 0.7,
                'max_tokens' => 2500
            ], 'summary');

            // Strip markdown JSON codeblocks if any
            $cleanResponse = preg_replace('/```json\s*(.*?)\s*```/s', '$1', $response);
            $cleanResponse = preg_replace('/```\s*(.*?)\s*```/s', '$1', $cleanResponse);
            $cleanResponse = trim($cleanResponse);
            
            $result = json_decode($cleanResponse, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $result;
            }
            
            Log::error('AI mengembalikan JSON yang tidak valid: ' . $response);
            return null;
        } catch (Exception $e) {
            Log::error('Koneksi ke AI API gagal: ' . $e->getMessage());
        }

        return null;
    }
}
