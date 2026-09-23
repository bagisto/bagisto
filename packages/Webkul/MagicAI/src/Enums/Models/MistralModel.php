<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum MistralModel: string implements AiModelContract
{
    /**
     * Rolling aliases that always resolve to Mistral's latest stable version.
     */
    case MistralLargeLatest = 'mistral-large-latest';
    case MistralMediumLatest = 'mistral-medium-latest';
    case MistralSmallLatest = 'mistral-small-latest';

    /**
     * Pinned current versions, ordered from most to least capable.
     */
    case MistralLarge3 = 'mistral-large-2512';
    case MistralMedium35 = 'mistral-medium-3-5';
    case MistralSmall4 = 'mistral-small-2603';

    /**
     * Get the SDK Lab provider this model belongs to.
     */
    public function provider(): Lab
    {
        return Lab::Mistral;
    }

    /**
     * Get the human-readable display name.
     */
    public function label(): string
    {
        return match ($this) {
            self::MistralLargeLatest => 'Mistral Large (Latest)',
            self::MistralMediumLatest => 'Mistral Medium (Latest)',
            self::MistralSmallLatest => 'Mistral Small (Latest)',
            self::MistralLarge3 => 'Mistral Large 3',
            self::MistralMedium35 => 'Mistral Medium 3.5',
            self::MistralSmall4 => 'Mistral Small 4',
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
        return self::MistralSmallLatest;
    }

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static
    {
        return null;
    }
}
