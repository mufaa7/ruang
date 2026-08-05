<?php

namespace App\Services\AI;

use Illuminate\Support\Manager;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use App\Services\AI\Providers\NineInferenceProvider;
// use App\Services\AI\Providers\GeminiProvider; // For future phase
use App\Services\AI\Providers\KelontongProvider;
// use App\Services\AI\Providers\OpenRouterProvider; // For future phase
use App\Services\AI\Contracts\AiProviderInterface;

class AIManager extends Manager
{
    /**
     * Get the default driver name.
     */
    public function getDefaultDriver()
    {
        return $this->getSettings()['active_ai_provider'] ?? 'nineinference';
    }

    /**
     * Get all cached AI settings.
     */
    protected function getSettings(): array
    {
        return Cache::rememberForever('ai_settings', function () {
            return Setting::where('key', 'like', 'ai_%')
                ->orWhere('key', 'active_ai_provider')
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Create an instance of the NineInference driver.
     */
    public function createNineinferenceDriver(): AiProviderInterface
    {
        $settings = $this->getSettings();
        
        // Coba ambil dari DB (terenkripsi), fallback ke .env
        $encryptedKey = $settings['ai_nineinference_key'] ?? null;
        $apiKey = $encryptedKey ? Crypt::decryptString($encryptedKey) : config('services.nineinference.key');
        
        $baseUrl = $settings['ai_nineinference_base_url'] ?? config('services.nineinference.base_url', 'https://9inference.cloud/v1');

        return new NineInferenceProvider($apiKey, $baseUrl);
    }
    
    public function createKelontongDriver(): AiProviderInterface
    {
        $settings = $this->getSettings();
        
        $encryptedKey = $settings['ai_kelontong_key'] ?? null;
        $apiKey = $encryptedKey ? Crypt::decryptString($encryptedKey) : '';
        
        $baseUrl = $settings['ai_kelontong_base_url'] ?? 'https://api.kelontongai.my.id/v1';

        return new KelontongProvider($apiKey, $baseUrl);
    }
    
    // Future providers will be added here
    // public function createGeminiDriver(): AiProviderInterface
    // public function createOpenrouterDriver(): AiProviderInterface

    /**
     * Get the registry of supported AI providers.
     * New providers can be added here without touching the UI blade.
     */
    public function getSupportedProviders(): array
    {
        return [
            'nineinference' => [
                'name' => 'NineInference',
                'default_base_url' => 'https://9inference.cloud/v1',
                'status_indicator' => true,
                'class' => \App\Services\AI\Providers\NineInferenceProvider::class,
            ],
            'kelontong' => [
                'name' => 'Kelontong AI',
                'default_base_url' => 'https://api.kelontongai.my.id/v1',
                'status_indicator' => true,
                'class' => \App\Services\AI\Providers\KelontongProvider::class,
            ],
            'gemini' => [
                'name' => 'Google Gemini',
                'default_base_url' => 'https://generativelanguage.googleapis.com/v1beta',
                'status_indicator' => false,
                'class' => null, // To be implemented
            ],
            'openrouter' => [
                'name' => 'OpenRouter',
                'default_base_url' => 'https://openrouter.ai/api/v1',
                'status_indicator' => false,
                'class' => null, // To be implemented
            ],
        ];
    }

    /**
     * Get the registry of supported AI features.
     */
    public function getSupportedFeatures(): array
    {
        return [
            'makalah' => [
                'name' => 'Makalah AI',
                'icon' => '📄'
            ],
            'quiz' => [
                'name' => 'Quiz & Flashcard',
                'icon' => '❓'
            ],
            'summary' => [
                'name' => 'Rangkuman',
                'icon' => '📝'
            ],
            'duck' => [
                'name' => 'Duck Mascot',
                'icon' => '🦆'
            ]
        ];
    }

    /**
     * Proxy chat method to the resolved driver (for convenience, optionally we can just return the driver).
     * We pass the base configuration here so Controllers/Prompts don't need to know the default config.
     */
    public function chat(array $messages, array $config = [], ?string $feature = null)
    {
        $settings = $this->getSettings();
        
        // Default Global Config
        $providerName = $this->getDefaultDriver();
        $modelName = $settings["ai_{$providerName}_model"] ?? null;
        $timeout = $settings["ai_{$providerName}_timeout"] ?? 120;
        $retry = $settings["ai_{$providerName}_retry"] ?? 0;

        // Feature Override
        if ($feature) {
            $useDefault = filter_var($settings["ai_{$feature}_use_default"] ?? true, FILTER_VALIDATE_BOOLEAN);
            if (!$useDefault) {
                $featureProvider = $settings["ai_{$feature}_provider"] ?? 'default';
                if ($featureProvider !== 'default' && !empty($featureProvider)) {
                    $providerName = $featureProvider;
                    // If overriden, use the provider's specific setting or feature-specific model
                    $modelName = $settings["ai_{$feature}_model"] ?? ($settings["ai_{$providerName}_model"] ?? null);
                    $timeout = $settings["ai_{$providerName}_timeout"] ?? 120;
                    $retry = $settings["ai_{$providerName}_retry"] ?? 0;
                }
            }
        }
        
        // Merge request config with global DB settings
        $mergedConfig = array_merge([
            'model' => $modelName,
            'temperature' => isset($settings['ai_temperature']) ? (float) $settings['ai_temperature'] : 0.7,
            'max_tokens' => isset($settings['ai_max_tokens']) ? (int) $settings['ai_max_tokens'] : null,
            'timeout' => (int) $timeout,
            'retry' => (int) $retry,
            'feature_name' => $feature
        ], $config);

        $maxAttempts = max(1, (int) $mergedConfig['retry']);
        // Override with minimum 3 attempts for rate limits if user set retry to 0
        if ($maxAttempts === 1) {
            $maxAttempts = 3; 
        }

        $attempts = 0;
        while ($attempts < $maxAttempts) {
            $attempts++;
            try {
                return $this->driver($providerName)->chat($messages, $mergedConfig);
            } catch (\Exception $e) {
                if ($attempts >= $maxAttempts) {
                    throw $e;
                }
                
                // Cek apakah error 429 (Rate Limit) atau timeout
                $errorMsg = strtolower($e->getMessage());
                if (str_contains($errorMsg, '429') || str_contains($errorMsg, 'rate limit') || str_contains($errorMsg, 'timeout')) {
                    // Jeda 3 detik sebelum mencoba lagi
                    sleep(3);
                    continue;
                }
                
                // Kalau error lain (misal API key salah), langsung lempar errornya
                throw $e;
            }
        }
    }
}
