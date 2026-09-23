<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum DeepSeekModel: string implements AiModelContract
{
    /**
     * Current DeepSeek V4 models, ordered from most to least capable.
     */
    case V4Pro = 'deepseek-v4-pro';
    case Flash = 'deepseek-flash';

    /**
     * Get the SDK Lab provider this model belongs to.
     */
    public function provider(): Lab
    {
        return Lab::DeepSeek;
    }

    /**
     * Get the human-readable display name.
     */
    public function label(): string
    {
        return match ($this) {
            self::V4Pro => 'DeepSeek V4 Pro',
            self::Flash => 'DeepSeek V4.1 Flash',
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
        return self::Flash;
    }

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static
    {
        return null;
    }
}
