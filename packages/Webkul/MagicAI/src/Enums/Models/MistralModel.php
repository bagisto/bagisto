<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum MistralModel: string implements AiModelContract
{
    /**
     * Versioned (pinned) model IDs.
     */
    case MistralLarge3 = 'mistral-large-2512';
    case MistralMedium31 = 'mistral-medium-2508';
    case MistralSmall32 = 'mistral-small-2506';
    case MagistralMedium = 'magistral-medium-2509';
    case MagistralSmall = 'magistral-small-2509';

    /**
     * Rolling aliases that always resolve to the latest stable release.
     */
    case MistralLarge = 'mistral-large-latest';
    case MistralSmall = 'mistral-small-latest';
    case Codestral = 'codestral-latest';

    /**
     * Get the SDK Lab provider this model belongs to.
     */
    public function provider(): Lab
    {
        return Lab::Mistral;
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
     * Get the human-readable display name.
     */
    public function label(): string
    {
        return match ($this) {
            self::MistralLarge3 => 'Mistral Large 3',
            self::MistralMedium31 => 'Mistral Medium 3.1',
            self::MistralSmall32 => 'Mistral Small 3.2',
            self::MagistralMedium => 'Magistral Medium',
            self::MagistralSmall => 'Magistral Small',
            self::MistralLarge => 'Mistral Large (Latest)',
            self::MistralSmall => 'Mistral Small (Latest)',
            self::Codestral => 'Codestral (Latest)',
        };
    }

    /**
     * Get the recommended default model for text generation.
     */
    public static function defaultTextModel(): ?static
    {
        return self::MistralSmall;
    }

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static
    {
        return null;
    }
}
