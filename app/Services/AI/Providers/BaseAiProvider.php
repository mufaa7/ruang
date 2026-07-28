<?php

namespace App\Services\AI\Providers;

use App\Services\AI\Contracts\AiProviderInterface;
use App\Models\AiUsageLog;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class BaseAiProvider implements AiProviderInterface
{
    /**
     * Record usage log securely via centralized method.
     */
    protected function logUsage(
        int $promptTokens,
        int $completionTokens,
        int $totalTokens,
        float $latency,
        string $model,
        string $status = 'success',
        ?string $errorMessage = null,
        string $featureName = 'unknown'
    ): void {
        try {
            // Note: estimated_cost logic could be added here based on provider/model pricing.
            $estimatedCost = 0; // default for now

            AiUsageLog::create([
                'provider'          => $this->getProviderName(),
                'prompt_tokens'     => $promptTokens,
                'completion_tokens' => $completionTokens,
                'total_tokens'      => $totalTokens,
                'estimated_cost'    => $estimatedCost,
                'model'             => $model,
                'duration'          => $latency,
                'user_id'           => auth()->id(),
                'feature_name'      => $featureName,
                'status'            => $status,
                'error_message'     => $errorMessage,
                'ip_address'        => request()->ip(),
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to write AiUsageLog', ['error' => $e->getMessage()]);
        }
    }
}
