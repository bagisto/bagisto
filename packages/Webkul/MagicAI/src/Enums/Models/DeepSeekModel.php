<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum DeepSeekModel: string implements AiModelContract
{
    /**
     * DeepSeek text models.
     */
    case Chat = 'deepseek-chat';
    case Reasoner = 'deepseek-reasoner';

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
            self::Chat => 'DeepSeek Chat',
            self::Reasoner => 'DeepSeek Reasoner',
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
        return self::Chat;
    }

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static
    {
        return null;
    }
}
