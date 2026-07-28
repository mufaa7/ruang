<?php

namespace App\Services\AI\Providers;

use App\Services\AI\DTO\AiResponseDTO;
use Illuminate\Support\Facades\Http;
use Exception;

class KelontongProvider extends BaseAiProvider
{
    public function __construct(
        protected string $apiKey,
        protected string $baseUrl = 'https://api.kelontongai.my.id/v1'
    ) {
        if (empty($this->apiKey)) {
            throw new Exception("API Key untuk Kelontong AI belum diatur.");
        }
    }

    public function getProviderName(): string
    {
        return 'kelontong';
    }

    public function chat(array $messages, array $config = []): AiResponseDTO
    {
        $model = $config['model'] ?? 'deepseek-v4-pro';
        $featureName = $config['feature_name'] ?? 'unknown';
        
        $payload = [
            'model'       => $model,
            'messages'    => $messages,
            'temperature' => $config['temperature'] ?? 0.7,
        ];

        if (isset($config['max_tokens'])) {
            $payload['max_tokens'] = $config['max_tokens'];
        }

        $startTime = microtime(true);

        $response = Http::withToken($this->apiKey)
            ->connectTimeout($config['timeout'] ?? 10)
            ->timeout($config['timeout'] ?? 120)
            ->post($this->baseUrl . '/chat/completions', $payload);

        $latency = microtime(true) - $startTime;

        if ($response->successful()) {
            $usage = $response->json('usage', []);
            $promptTokens = $usage['prompt_tokens'] ?? 0;
            $completionTokens = $usage['completion_tokens'] ?? 0;
            $totalTokens = $usage['total_tokens'] ?? 0;
            
            $content = (string) $response->json('choices.0.message.content', '');

            $this->logUsage(
                promptTokens: $promptTokens,
                completionTokens: $completionTokens,
                totalTokens: $totalTokens,
                latency: $latency,
                model: $model,
                status: 'success',
                featureName: $featureName
            );

            return new AiResponseDTO(
                content: $content,
                promptTokens: $promptTokens,
                completionTokens: $completionTokens,
                totalTokens: $totalTokens,
                latency: $latency,
                model: $model,
                provider: $this->getProviderName()
            );
        }

        $statusCode = $response->status();
        $body = $response->body();
        
        $this->logUsage(
            promptTokens: 0,
            completionTokens: 0,
            totalTokens: 0,
            latency: $latency,
            model: $model,
            status: 'error',
            errorMessage: substr($body, 0, 500),
            featureName: $featureName
        );

        throw new Exception("Kelontong AI Error (HTTP {$statusCode}): " . substr($body, 0, 200));
    }
}
