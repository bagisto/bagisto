<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum OpenAiModel: string implements AiModelContract
{
    /**
     * Current GPT models, ordered from most to least capable.
     */
    case GPT6Astra = 'gpt-6-astra';
    case GPT56Sol = 'gpt-5.6-sol';
    case GPT56Terra = 'gpt-5.6-terra';
    case GPT56Luna = 'gpt-5.6-luna';

    /**
     * Previous GPT generation, still available.
     */
    case GPT41 = 'gpt-4.1';
    case GPT41Mini = 'gpt-4.1-mini';

    /**
     * Image generation models, ordered from most to least capable.
     */
    case GptImage25Sunburst = 'gpt-image-2.5-sunburst';
    case GptImage25Flare = 'gpt-image-2.5-flare';
    case GptImage2 = 'gpt-image-2';

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
            self::GPT6Astra => 'GPT-6 Astra',
            self::GPT56Sol => 'GPT-5.6 Sol',
            self::GPT56Terra => 'GPT-5.6 Terra',
            self::GPT56Luna => 'GPT-5.6 Luna',
            self::GPT41 => 'GPT-4.1',
            self::GPT41Mini => 'GPT-4.1 Mini',
            self::GptImage25Sunburst => 'GPT Image 2.5 Sunburst',
            self::GptImage25Flare => 'GPT Image 2.5 Flare',
            self::GptImage2 => 'GPT Image 2',
        };
    }

    /**
     * Determine whether this model generates images.
     */
    public function isImageModel(): bool
    {
        return match ($this) {
            self::GptImage25Sunburst, self::GptImage25Flare, self::GptImage2 => true,
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
        return self::GPT56Terra;
    }

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static
    {
        return self::GptImage25Flare;
    }
}
