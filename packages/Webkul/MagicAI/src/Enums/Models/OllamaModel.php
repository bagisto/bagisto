<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum OllamaModel: string implements AiModelContract
{
    /**
     * Common community models available via a local Ollama server.
     */
    case Llama4Scout = 'llama4:scout';
    case Llama33_70B = 'llama3.3:70b';
    case Llama32_3B = 'llama3.2:3b';
    case Llama32_1B = 'llama3.2:1b';
    case Llama31_8B = 'llama3.1:8b';
    case DeepSeekR1 = 'deepseek-r1:8b';
    case Qwen3_8B = 'qwen3:8b';
    case Qwen25_7B = 'qwen2.5:7b';
    case Mistral_7B = 'mistral:7b';
    case Phi4 = 'phi4';
    case Gemma3_9B = 'gemma3:9b';

    /**
     * Get the SDK Lab provider this model belongs to.
     */
    public function provider(): Lab
    {
        return Lab::Ollama;
    }

    /**
     * Determine whether this model generates images.
     */
    public function isImageModel(): bool
    {
        return false;
    }

    /**
     * Determine whether this model generates text.
     */
    public function isTextModel(): bool
    {
        return true;
    }

    /**
     * Get the human-readable display name.
     */
    public function label(): string
    {
        return match ($this) {
            self::Llama4Scout => 'LLaMA 4 Scout',
            self::Llama33_70B => 'LLaMA 3.3 70B',
            self::Llama32_3B => 'LLaMA 3.2 3B',
            self::Llama32_1B => 'LLaMA 3.2 1B',
            self::Llama31_8B => 'LLaMA 3.1 8B',
            self::DeepSeekR1 => 'DeepSeek R1 8B',
            self::Qwen3_8B => 'Qwen 3 8B',
            self::Qwen25_7B => 'Qwen 2.5 7B',
            self::Mistral_7B => 'Mistral 7B',
            self::Phi4 => 'Phi 4',
            self::Gemma3_9B => 'Gemma 3 9B',
        };
    }

    /**
     * Get the recommended default model for text generation.
     */
    public static function defaultTextModel(): ?static
    {
        return self::Llama32_3B;
    }

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static
    {
        return null;
    }
}
