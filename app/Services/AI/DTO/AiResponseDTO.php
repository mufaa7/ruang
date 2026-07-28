<?php

namespace App\Services\AI\DTO;

class AiResponseDTO
{
    public function __construct(
        public readonly string $content,
        public readonly int $promptTokens = 0,
        public readonly int $completionTokens = 0,
        public readonly int $totalTokens = 0,
        public readonly float $latency = 0,
        public readonly string $model = '',
        public readonly string $provider = ''
    ) {
    }
}
