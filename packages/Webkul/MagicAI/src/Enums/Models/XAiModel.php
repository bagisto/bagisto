<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum XAiModel: string implements AiModelContract
{
    /**
     * Grok 4 family (current frontier).
     */
    case Grok4 = 'grok-4';
    case Grok41Fast = 'grok-4-1-fast';

    /**
     * Grok 3 family (previous generation).
     */
    case Grok3 = 'grok-3';
    case Grok3Mini = 'grok-3-mini';

    /**
     * Image generation.
     */
    case GrokImagineImage = 'grok-imagine-image';
    case Grok2Image = 'grok-2-image';

    /**
     * Get the SDK Lab provider this model belongs to.
     */
    public function provider(): Lab
    {
        return Lab::xAI;
    }

    /**
     * Get the human-readable display name.
     */
    public function label(): string
    {
        return match ($this) {
            self::Grok4 => 'Grok 4',
            self::Grok41Fast => 'Grok 4.1 Fast',
            self::Grok3 => 'Grok 3',
            self::Grok3Mini => 'Grok 3 Mini',
            self::GrokImagineImage => 'Grok Imagine Image',
            self::Grok2Image => 'Grok 2 Image',
        };
    }

    /**
     * Determine whether this model generates images.
     */
    public function isImageModel(): bool
    {
        return match ($this) {
            self::GrokImagineImage, self::Grok2Image => true,
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
        return self::Grok3;
    }

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static
    {
        return self::GrokImagineImage;
    }
}
