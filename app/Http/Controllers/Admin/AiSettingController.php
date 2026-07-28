<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use App\Models\AiUsageLog;
use App\Services\AI\Providers\NineInferenceProvider;
use App\Services\AI\AIManager;
use Illuminate\Support\Facades\Log;
use Exception;

class AiSettingController extends Controller
{
    public function index(AIManager $aiManager)
    {
        // Ambil semua setting AI
        $settings = Setting::where('key', 'like', 'ai_%')
            ->orWhere('key', 'active_ai_provider')
            ->pluck('value', 'key')
            ->toArray();

        $providers = $aiManager->getSupportedProviders();
        $features = $aiManager->getSupportedFeatures();

        // Dekripsi key jika ada untuk ditampilkan di form (disamarkan sebagian)
        foreach ($providers as $prov => $data) {
            $keyName = "ai_{$prov}_key";
            if (!empty($settings[$keyName])) {
                try {
                    $decrypted = Crypt::decryptString($settings[$keyName]);
                    $settings[$keyName] = substr($decrypted, 0, 8) . '...' . substr($decrypted, -4);
                } catch (\Exception $e) {
                    $settings[$keyName] = '';
                }
            }
        }

        // Data untuk Mini Dashboard (Today)
        $todayLogs = AiUsageLog::whereDate('created_at', today());

        $stats = [
            'requests_today' => (int) $todayLogs->count(),
            'tokens_today'   => (int) $todayLogs->sum('total_tokens'),
            'cost_today'     => (float) $todayLogs->sum('estimated_cost'),
            'avg_latency'    => round((float) $todayLogs->avg('duration') ?? 0, 2),
        ];

        // Resolving active configuration for each feature for the UI Mapping
        $globalProvider = $settings['active_ai_provider'] ?? 'nineinference';
        $activeMapping = [];
        foreach ($features as $fKey => $fData) {
            $useDefault = filter_var($settings["ai_{$fKey}_use_default"] ?? '1', FILTER_VALIDATE_BOOLEAN);
            if ($useDefault) {
                $provider = $globalProvider;
                $model = $settings["ai_{$provider}_model"] ?? 'Not Set';
            } else {
                $provider = $settings["ai_{$fKey}_provider"] ?? $globalProvider;
                if ($provider === 'default') $provider = $globalProvider;
                $model = $settings["ai_{$fKey}_model"] ?? ($settings["ai_{$provider}_model"] ?? 'Not Set');
            }
            
            $activeMapping[$fKey] = [
                'provider' => $providers[$provider]['name'] ?? $provider,
                'model' => $model,
                'is_default' => $useDefault
            ];
        }

        return view('admin.ai-settings', compact('settings', 'providers', 'features', 'stats', 'activeMapping'));
    }

    public function store(Request $request, AIManager $aiManager)
    {
        $providers = $aiManager->getSupportedProviders();
        $features = $aiManager->getSupportedFeatures();
        $providerKeys = implode(',', array_keys($providers));

        $rules = [
            'active_ai_provider' => "required|string|in:{$providerKeys}",
            'ai_temperature' => 'nullable|numeric|min:0|max:2',
            'ai_max_tokens' => 'nullable|integer|min:1',
        ];

        foreach ($providers as $key => $data) {
            $rules["ai_{$key}_key"] = 'nullable|string';
            $rules["ai_{$key}_base_url"] = 'nullable|string|url';
            $rules["ai_{$key}_model"] = 'nullable|string';
            $rules["ai_{$key}_timeout"] = 'nullable|integer|min:1';
            $rules["ai_{$key}_retry"] = 'nullable|integer|min:0';
        }

        foreach ($features as $fKey => $fData) {
            $rules["ai_{$fKey}_use_default"] = 'nullable|boolean';
            $rules["ai_{$fKey}_provider"] = "nullable|string|in:{$providerKeys},default";
            $rules["ai_{$fKey}_model"] = 'nullable|string';
        }

        // Fix checkbox "on" values before validation
        foreach ($features as $fKey => $fData) {
            if ($request->has("ai_{$fKey}_use_default") && $request->input("ai_{$fKey}_use_default") === 'on') {
                $request->merge(["ai_{$fKey}_use_default" => '1']);
            } elseif (!$request->has("ai_{$fKey}_use_default")) {
                $request->merge(["ai_{$fKey}_use_default" => '0']);
            }
        }

        Log::info("INCOMING REQUEST TO AI SETTINGS", $request->all());
        $validated = $request->validate($rules);

        Log::info("Validated data from ai settings", $validated);

        foreach ($validated as $key => $value) {
            // Khusus API key, enkripsi jika ada nilainya dan bukan mask string (...)
            if (str_ends_with($key, '_key') && !empty($value)) {
                if (str_contains($value, '...')) {
                    continue; // Abaikan jika ini mask string
                }
                $value = Crypt::encryptString($value);
            }

            if ($value !== null) {
                // Untuk checkbox boolean on
                if ($value === 'on') {
                    $value = '1';
                }
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => (string) $value]
                );
            } else {
                // Hapus setting jika null agar tidak nyangkut (terutama untuk override feature config)
                if (!str_ends_with($key, '_use_default')) {
                    Setting::where('key', $key)->delete();
                }
            }
        }

        // Clear cache
        Cache::forget('ai_settings');

        return back()->with('success', 'Pengaturan AI berhasil disimpan.');
    }

    public function testConnection(Request $request, AIManager $aiManager)
    {
        $provider = $request->input('provider');
        $apiKey = $request->input('api_key');
        $baseUrl = $request->input('base_url');
        
        // Cek jika API key masih mask "...", ambil aslinya dari setting
        if (str_contains($apiKey, '...')) {
            $keyName = "ai_{$provider}_key";
            $savedEncrypted = Setting::where('key', $keyName)->value('value');
            if ($savedEncrypted) {
                try {
                    $apiKey = Crypt::decryptString($savedEncrypted);
                } catch (\Exception $e) {
                    return response()->json(['success' => false, 'message' => 'Gagal mendekripsi API Key lama.']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'API Key tidak ditemukan.']);
            }
        }

        $providers = $aiManager->getSupportedProviders();
        if (!isset($providers[$provider])) {
            return response()->json(['success' => false, 'message' => 'Provider tidak terdaftar.']);
        }

        $providerClass = $providers[$provider]['class'];
        if (!$providerClass) {
            return response()->json(['success' => false, 'message' => 'Provider ini belum didukung untuk testing.']);
        }

        try {
            $aiProvider = new $providerClass($apiKey, $baseUrl ?? $providers[$provider]['default_base_url']);

            $messages = [
                ['role' => 'user', 'content' => 'Hello. Reply with only one word: OK']
            ];
            
            $config = [
                'model' => $request->input('model', 'deepseek-v4-pro'), // Use provided or fallback
                'max_tokens' => 10,
                'timeout' => 10
            ];

            $response = $aiProvider->chat($messages, $config);

            return response()->json([
                'success' => true,
                'latency' => round($response->latency * 1000) . ' ms',
                'message' => 'Connected successfully!'
            ]);

        } catch (Exception $e) {
            Log::error("Test connection failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
