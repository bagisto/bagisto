<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum GroqModel: string implements AiModelContract
{
    /**
     * Groq-hosted production models, ordered from most to least capable.
     */
    case GptOss120B = 'openai/gpt-oss-120b';
    case GptOss20B = 'openai/gpt-oss-20b';

    /**
     * Get the SDK Lab provider this model belongs to.
     */
    public function provider(): Lab
    {
        return Lab::Groq;
    }

    /**
     * Get the human-readable display name.
     */
    public function label(): string
    {
        return match ($this) {
            self::GptOss120B => 'GPT-OSS 120B',
            self::GptOss20B => 'GPT-OSS 20B',
        };
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
     * Get the recommended default model for text generation.
     */
    public static function defaultTextModel(): ?static
    {
        return self::GptOss20B;
    }

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static
    {
        return null;
    }
}
