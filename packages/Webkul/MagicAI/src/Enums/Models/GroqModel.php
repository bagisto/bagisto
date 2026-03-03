<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum GroqModel: string implements AiModelContract
{
    /**
     * LLaMA models hosted on Groq.
     */
    case Llama4Scout = 'meta-llama/llama-4-scout-17b-16e-instruct';
    case Llama33_70B = 'llama-3.3-70b-versatile';
    case Llama31_8B = 'llama-3.1-8b-instant';

    /**
     * Get the SDK Lab provider this model belongs to.
     */
    public function provider(): Lab
    {
        return Lab::Groq;
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
            self::Llama4Scout => 'LLaMA 4 Scout 17B',
            self::Llama33_70B => 'LLaMA 3.3 70B',
            self::Llama31_8B => 'LLaMA 3.1 8B',
        };
    }

    /**
     * Get the recommended default model for text generation.
     */
    public static function defaultTextModel(): ?static
    {
        return self::Llama33_70B;
    }

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static
    {
        return null;
    }
}
