<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum GeminiModel: string implements AiModelContract
{
    /**
     * Gemini 3 (latest generation — preview).
     */
    case Gemini31Pro = 'gemini-3.1-pro-preview';
    case Gemini3Flash = 'gemini-3-flash-preview';

    /**
     * Gemini 2.5 (current stable — recommended for production).
     */
    case Gemini25Pro = 'gemini-2.5-pro';
    case Gemini25Flash = 'gemini-2.5-flash';
    case Gemini25FlashLite = 'gemini-2.5-flash-lite';

    /**
     * Imagen 4 family.
     */
    case Imagen4 = 'imagen-4.0-generate-001';
    case Imagen4Ultra = 'imagen-4.0-ultra-generate-001';
    case Imagen4Fast = 'imagen-4.0-fast-generate-001';

    /**
     * Imagen 3 (previous gen — still available).
     */
    case Imagen3 = 'imagen-3.0-generate-002';

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
            self::Gemini31Pro => 'Gemini 3.1 Pro',
            self::Gemini3Flash => 'Gemini 3 Flash',
            self::Gemini25Pro => 'Gemini 2.5 Pro',
            self::Gemini25Flash => 'Gemini 2.5 Flash',
            self::Gemini25FlashLite => 'Gemini 2.5 Flash Lite',
            self::Imagen4 => 'Imagen 4',
            self::Imagen4Ultra => 'Imagen 4 Ultra',
            self::Imagen4Fast => 'Imagen 4 Fast',
            self::Imagen3 => 'Imagen 3',
        };
    }

    /**
     * Determine whether this model generates images.
     */
    public function isImageModel(): bool
    {
        return match ($this) {
            self::Imagen4, self::Imagen4Ultra, self::Imagen4Fast, self::Imagen3 => true,
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
        return self::Gemini3Flash;
    }

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static
    {
        return self::Imagen4;
    }
}
