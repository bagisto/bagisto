<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum GroqModel: string implements AiModelContract
{
    /**
     * Groq-hosted LLaMA 3 family.
     *
     * Note: LLaMA 3.3 70B is the most capable model currently available on Groq, and is recommended for most use cases.
     */
    case Llama33_70B = 'llama-3.3-70b-versatile';
    case Llama31_8B = 'llama-3.1-8b-instant';

    /**
     * Groq-hosted open-weight models.
     */
    case GptOss120B = 'openai/gpt-oss-120b';
    case GptOss20B = 'openai/gpt-oss-20b';
    case KimiK2 = 'moonshotai/kimi-k2-instruct-0905';
    case Qwen3_32B = 'qwen/qwen3-32b';

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
            self::Llama33_70B => 'LLaMA 3.3 70B',
            self::Llama31_8B => 'LLaMA 3.1 8B',
            self::GptOss120B => 'GPT-OSS 120B',
            self::GptOss20B => 'GPT-OSS 20B',
            self::KimiK2 => 'Kimi K2',
            self::Qwen3_32B => 'Qwen 3 32B',
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
        return self::Llama31_8B;
    }

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static
    {
        return null;
    }
}
