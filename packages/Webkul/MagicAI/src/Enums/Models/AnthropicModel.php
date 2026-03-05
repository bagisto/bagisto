<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum AnthropicModel: string implements AiModelContract
{
    /**
     * Current Anthropic text models (ordered from most to least capable). All are suitable for chat
     * and non-chat use cases.
     */
    case ClaudeOpus46 = 'claude-opus-4-6';
    case ClaudeSonnet46 = 'claude-sonnet-4-6';
    case ClaudeHaiku45 = 'claude-haiku-4-5-20251001';

    /**
     * Older Anthropic text models (ordered from most to least capable). All are suitable for chat
     * and non-chat use cases.
     */
    case ClaudeOpus45 = 'claude-opus-4-5-20251101';
    case ClaudeSonnet45 = 'claude-sonnet-4-5-20250929';
    case ClaudeSonnet4 = 'claude-sonnet-4-20250514';

    /**
     * Get the SDK Lab provider this model belongs to.
     */
    public function provider(): Lab
    {
        return Lab::Anthropic;
    }

    /**
     * Get the human-readable display name.
     */
    public function label(): string
    {
        return match ($this) {
            self::ClaudeOpus46 => 'Claude Opus 4.6',
            self::ClaudeOpus45 => 'Claude Opus 4.5',
            self::ClaudeSonnet46 => 'Claude Sonnet 4.6',
            self::ClaudeSonnet45 => 'Claude Sonnet 4.5',
            self::ClaudeSonnet4 => 'Claude Sonnet 4',
            self::ClaudeHaiku45 => 'Claude Haiku 4.5',
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
        return self::ClaudeHaiku45;
    }

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static
    {
        return null;
    }
}
