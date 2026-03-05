<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum OpenAiModel: string implements AiModelContract
{
    /**
     * GPT-5 family (frontier) — current recommended models.
     */
    case GPT52 = 'gpt-5.2';
    case GPT51 = 'gpt-5.1';
    case GPT5 = 'gpt-5';
    case GPT5Mini = 'gpt-5-mini';
    case GPT5Nano = 'gpt-5-nano';

    /**
     * GPT-4 family.
     */
    case GPT41 = 'gpt-4.1';
    case GPT41Mini = 'gpt-4.1-mini';
    case GPT41Nano = 'gpt-4.1-nano';

    /**
     * Image generation models.
     */
    case GptImage15 = 'gpt-image-1.5';
    case GptImage1 = 'gpt-image-1';

    /**
     * Get the SDK Lab provider this model belongs to.
     */
    public function provider(): Lab
    {
        return Lab::OpenAI;
    }

    /**
     * Get the human-readable display name.
     */
    public function label(): string
    {
        return match ($this) {
            self::GPT52 => 'GPT-5.2',
            self::GPT51 => 'GPT-5.1',
            self::GPT5 => 'GPT-5',
            self::GPT5Mini => 'GPT-5 Mini',
            self::GPT5Nano => 'GPT-5 Nano',
            self::GPT41 => 'GPT-4.1',
            self::GPT41Mini => 'GPT-4.1 Mini',
            self::GPT41Nano => 'GPT-4.1 Nano',
            self::GptImage15 => 'GPT Image 1.5',
            self::GptImage1 => 'GPT Image 1',
        };
    }

    /**
     * Determine whether this model generates images.
     */
    public function isImageModel(): bool
    {
        return match ($this) {
            self::GptImage15, self::GptImage1 => true,
            default => false,
        };
    }

    /**
     * Determine whether this model generates text.
     */
    public function isTextModel(): bool
    {
        return ! $this->isImageModel();
    }

    /**
     * Get the recommended default model for text generation.
     */
    public static function defaultTextModel(): ?static
    {
        return self::GPT41;
    }

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static
    {
        return self::GptImage1;
    }
}
