<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum MistralModel: string implements AiModelContract
{
    /**
     * Rolling aliases — always resolve to latest stable.
     */
    case MistralLargeLatest = 'mistral-large-latest';
    case MistralMediumLatest = 'mistral-medium-latest';
    case MistralSmallLatest = 'mistral-small-latest';

    /**
     * Mistral 3 family (latest generation — recommended for most use cases). All are suitable for chat and non-chat use cases.
     *
     * Note: Mistral Large 3 is the most capable model currently available, and is recommended for most use cases.
     */
    case MistralLarge3 = 'mistral-large-2512';
    case MistralMedium31 = 'mistral-medium-2508';
    case MistralSmall32 = 'mistral-small-2506';

    /**
     * Older Mistral 2 family (previous generation — still available). All are suitable for chat and non-chat use cases.
     */
    case MagistralMedium = 'magistral-medium-2509';
    case MagistralSmall = 'magistral-small-2509';

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
            self::MistralMedium31 => 'Mistral Medium 3.1',
            self::MistralSmall32 => 'Mistral Small 3.2',
            self::MagistralMedium => 'Magistral Medium',
            self::MagistralSmall => 'Magistral Small',
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
