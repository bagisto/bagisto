<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum AnthropicModel: string implements AiModelContract
{
    /**
     * Current Claude models, ordered from most to least capable.
     */
    case ClaudeFable51 = 'claude-fable-5-1';
    case ClaudeOpus5 = 'claude-opus-5';
    case ClaudeSonnet5 = 'claude-sonnet-5';
    case ClaudeHaiku45 = 'claude-haiku-4-5-20251001';

    /**
     * Previous Claude models, still available, ordered from most to least capable.
     */
    case ClaudeOpus46 = 'claude-opus-4-6';
    case ClaudeSonnet46 = 'claude-sonnet-4-6';
    case ClaudeOpus45 = 'claude-opus-4-5-20251101';
    case ClaudeSonnet45 = 'claude-sonnet-4-5-20250929';

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
            self::ClaudeFable51 => 'Claude Fable 5.1',
            self::ClaudeOpus5 => 'Claude Opus 5',
            self::ClaudeSonnet5 => 'Claude Sonnet 5',
            self::ClaudeHaiku45 => 'Claude Haiku 4.5',
            self::ClaudeOpus46 => 'Claude Opus 4.6',
            self::ClaudeSonnet46 => 'Claude Sonnet 4.6',
            self::ClaudeOpus45 => 'Claude Opus 4.5',
            self::ClaudeSonnet45 => 'Claude Sonnet 4.5',
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
        return self::ClaudeSonnet5;
    }

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static
    {
        return null;
    }
}
