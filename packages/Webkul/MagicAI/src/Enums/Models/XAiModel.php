<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum XAiModel: string implements AiModelContract
{
    /**
     * Grok text models.
     */
    case Grok3 = 'grok-3';
    case Grok3Mini = 'grok-3-mini';
    case Grok2Latest = 'grok-2-latest';
    case Grok2 = 'grok-2-1212';

    /**
     * Aurora image model.
     */
    case Aurora = 'aurora';

    /**
     * Get the SDK Lab provider this model belongs to.
     */
    public function provider(): Lab
    {
        return Lab::xAI;
    }

    /**
     * Determine whether this model generates images.
     */
    public function isImageModel(): bool
    {
        return $this === self::Aurora;
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
            self::Grok3 => 'Grok 3',
            self::Grok3Mini => 'Grok 3 Mini',
            self::Grok2Latest => 'Grok 2 Latest',
            self::Grok2 => 'Grok 2',
            self::Aurora => 'Aurora',
        };
    }

    /**
     * Get the recommended default model for text generation.
     */
    public static function defaultTextModel(): ?static
    {
        return self::Grok3;
    }

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static
    {
        return self::Aurora;
    }
}
