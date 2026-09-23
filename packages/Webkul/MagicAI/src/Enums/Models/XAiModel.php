<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum XAiModel: string implements AiModelContract
{
    /**
     * Current Grok models, ordered from most to least capable.
     */
    case Grok47 = 'grok-4.7';
    case Grok46 = 'grok-4.6';
    case Grok45 = 'grok-4.5';
    case Grok43 = 'grok-4.3';
    case Grok420NonReasoning = 'grok-4.20-0309-non-reasoning';

    /**
     * Image generation models, ordered from most to least capable.
     */
    case GrokImagineImage2 = 'grok-imagine-image-2.0';
    case GrokImagineImage = 'grok-imagine-image';

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
            self::Grok47 => 'Grok 4.7',
            self::Grok46 => 'Grok 4.6',
            self::Grok45 => 'Grok 4.5',
            self::Grok43 => 'Grok 4.3',
            self::Grok420NonReasoning => 'Grok 4.20 (Non-Reasoning)',
            self::GrokImagineImage2 => 'Grok Imagine Image 2.0',
            self::GrokImagineImage => 'Grok Imagine Image',
        };
    }

    /**
     * Determine whether this model generates images.
     */
    public function isImageModel(): bool
    {
        return match ($this) {
            self::GrokImagineImage2, self::GrokImagineImage => true,
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
        return self::Grok43;
    }

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static
    {
        return self::GrokImagineImage;
    }
}
