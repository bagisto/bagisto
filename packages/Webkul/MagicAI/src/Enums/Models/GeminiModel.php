<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum GeminiModel: string implements AiModelContract
{
    /**
     * Current stable Gemini models, ordered from most to least capable.
     */
    case Gemini38Flash = 'gemini-3.8-flash';
    case Gemini37Flash = 'gemini-3.7-flash';
    case Gemini36Flash = 'gemini-3.6-flash';
    case Gemini35FlashLite = 'gemini-3.5-flash-lite';
    case Gemini31FlashLite = 'gemini-3.1-flash-lite';

    /**
     * Gemini models still in preview.
     */
    case Gemini31Pro = 'gemini-3.1-pro-preview';
    case Gemini3Flash = 'gemini-3-flash-preview';

    /**
     * Image generation models (Nano Banana), ordered from most to least capable.
     */
    case Gemini3ProImage = 'gemini-3-pro-image';
    case Gemini31FlashImage = 'gemini-3.1-flash-image';
    case Gemini31FlashLiteImage = 'gemini-3.1-flash-lite-image';

    /**
     * Get the SDK Lab provider this model belongs to.
     */
    public function provider(): Lab
    {
        return Lab::Gemini;
    }

    /**
     * Get the human-readable display name.
     */
    public function label(): string
    {
        return match ($this) {
            self::Gemini38Flash => 'Gemini 3.8 Flash',
            self::Gemini37Flash => 'Gemini 3.7 Flash',
            self::Gemini36Flash => 'Gemini 3.6 Flash',
            self::Gemini35FlashLite => 'Gemini 3.5 Flash-Lite',
            self::Gemini31FlashLite => 'Gemini 3.1 Flash-Lite',
            self::Gemini31Pro => 'Gemini 3.1 Pro (Preview)',
            self::Gemini3Flash => 'Gemini 3 Flash (Preview)',
            self::Gemini3ProImage => 'Nano Banana Pro (Gemini 3 Pro Image)',
            self::Gemini31FlashImage => 'Nano Banana 2 (Gemini 3.1 Flash Image)',
            self::Gemini31FlashLiteImage => 'Nano Banana 2 Lite (Gemini 3.1 Flash-Lite Image)',
        };
    }

    /**
     * Determine whether this model generates images.
     */
    public function isImageModel(): bool
    {
        return match ($this) {
            self::Gemini3ProImage, self::Gemini31FlashImage, self::Gemini31FlashLiteImage => true,
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
        return self::Gemini38Flash;
    }

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static
    {
        return self::Gemini31FlashImage;
    }
}
