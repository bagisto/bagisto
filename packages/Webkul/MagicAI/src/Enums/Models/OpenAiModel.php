<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum OpenAiModel: string implements AiModelContract
{
    /**
     * GPT-5 family (frontier).
     */
    case GPT52 = 'gpt-5.2';
    case GPT52Pro = 'gpt-5.2-pro';
    case GPT5 = 'gpt-5';
    case GPT5Mini = 'gpt-5-mini';
    case GPT5Nano = 'gpt-5-nano';

    /**
     * GPT-4 family.
     */
    case GPT41 = 'gpt-4.1';
    case GPT4O = 'gpt-4o';
    case GPT4OMini = 'gpt-4o-mini';
    case GPT4Turbo = 'gpt-4-turbo';

    /**
     * o-series reasoning models.
     */
    case O4Mini = 'o4-mini';
    case O3 = 'o3';
    case O3Mini = 'o3-mini';
    case O1 = 'o1';

    /**
     * Image generation models.
     */
    case GptImage15 = 'gpt-image-1.5';
    case GptImage1 = 'gpt-image-1';
    case DallE3 = 'dall-e-3';

    /**
     * Get the SDK Lab provider this model belongs to.
     */
    public function provider(): Lab
    {
        return Lab::OpenAI;
    }

    /**
     * Determine whether this model generates images.
     */
    public function isImageModel(): bool
    {
        return match ($this) {
            self::GptImage15, self::GptImage1, self::DallE3 => true,
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
     * Get the human-readable display name.
     */
    public function label(): string
    {
        return match ($this) {
            self::GPT52 => 'GPT-5.2',
            self::GPT52Pro => 'GPT-5.2 Pro',
            self::GPT5 => 'GPT-5',
            self::GPT5Mini => 'GPT-5 Mini',
            self::GPT5Nano => 'GPT-5 Nano',
            self::GPT41 => 'GPT-4.1',
            self::GPT4O => 'GPT-4o',
            self::GPT4OMini => 'GPT-4o Mini',
            self::GPT4Turbo => 'GPT-4 Turbo',
            self::O4Mini => 'o4 Mini',
            self::O3 => 'o3',
            self::O3Mini => 'o3 Mini',
            self::O1 => 'o1',
            self::GptImage15 => 'GPT Image 1.5',
            self::GptImage1 => 'GPT Image 1',
            self::DallE3 => 'DALL-E 3',
        };
    }

    /**
     * Get the recommended default model for text generation.
     */
    public static function defaultTextModel(): ?static
    {
        return self::GPT4OMini;
    }

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static
    {
        return self::GptImage1;
    }
}
