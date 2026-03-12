<?php

namespace Webkul\MagicAI\Enums\Contracts;

use Laravel\Ai\Enums\Lab;

interface AiModelContract
{
    /**
     * Get the SDK Lab provider this model belongs to.
     */
    public function provider(): Lab;

    /**
     * Get the human-readable display name.
     */
    public function label(): string;

    /**
     * Determine whether this model generates images.
     */
    public function isImageModel(): bool;

    /**
     * Determine whether this model generates text.
     */
    public function isTextModel(): bool;

    /**
     * Get the recommended default model for text generation.
     */
    public static function defaultTextModel(): ?static;

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static;
}
