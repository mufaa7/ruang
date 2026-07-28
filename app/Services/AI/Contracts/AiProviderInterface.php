<?php

namespace App\Services\AI\Contracts;

use App\Services\AI\DTO\AiResponseDTO;

interface AiProviderInterface
{
    /**
     * Get the provider identifier (e.g. 'nineinference', 'gemini')
     */
    public function getProviderName(): string;

    /**
     * Send a chat request to the AI provider.
     * 
     * @param array $messages Standard format [['role' => 'user', 'content' => '...']]
     * @param array $config Optional configuration (model, max_tokens, temperature, etc.)
     * @return AiResponseDTO
     */
    public function chat(array $messages, array $config = []): AiResponseDTO;
}
