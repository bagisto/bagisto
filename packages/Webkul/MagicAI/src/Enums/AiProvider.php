<?php

namespace Webkul\MagicAI\Enums;

use Laravel\Ai\Enums\Lab;

enum AiProvider: string
{
    /**
     * Text + image providers.
     */
    case Anthropic = 'anthropic';
    case Azure = 'azure';
    case DeepSeek = 'deepseek';
    case Gemini = 'gemini';
    case Groq = 'groq';
    case Mistral = 'mistral';
    case Ollama = 'ollama';
    case OpenAI = 'openai';
    case xAI = 'xai';

    /**
     * Return the equivalent SDK Lab case.
     */
    public function toLab(): Lab
    {
        return Lab::from($this->value);
    }

    public function label(): string
    {
        return match ($this) {
            self::Anthropic => 'Anthropic',
            self::Azure => 'Azure OpenAI',
            self::DeepSeek => 'DeepSeek',
            self::Gemini => 'Gemini',
            self::Groq => 'Groq',
            self::Mistral => 'Mistral',
            self::Ollama => 'Ollama',
            self::OpenAI => 'OpenAI',
            self::xAI => 'xAI',
        };
    }

    /**
     * Whether this provider supports text / chat generation.
     */
    public function supportsText(): bool
    {
        return in_array($this, self::textProviders(), true);
    }

    /**
     * Whether this provider supports image generation.
     */
    public function supportsImages(): bool
    {
        return in_array($this, self::imageProviders(), true);
    }

    /**
     * All providers that support text generation.
     *
     * @return self[]
     */
    public static function textProviders(): array
    {
        return [
            self::Anthropic,
            self::Azure,
            self::DeepSeek,
            self::Gemini,
            self::Groq,
            self::Mistral,
            self::Ollama,
            self::OpenAI,
            self::xAI,
        ];
    }

    /**
     * All providers that support image generation.
     *
     * @return self[]
     */
    public static function imageProviders(): array
    {
        return [
            self::Gemini,
            self::OpenAI,
            self::xAI,
        ];
    }

    /**
     * Text providers as [{title, value}] for system config select fields.
     *
     * @return array<int, array{title: string, value: string}>
     */
    public static function textProviderOptions(): array
    {
        return self::buildOptions(self::textProviders());
    }

    /**
     * Image providers as [{title, value}] for system config select fields.
     *
     * @return array<int, array{title: string, value: string}>
     */
    public static function imageProviderOptions(): array
    {
        return self::buildOptions(self::imageProviders());
    }

    /**
     * @param  self[]  $providers
     * @return array<int, array{title: string, value: string}>
     */
    private static function buildOptions(array $providers): array
    {
        return array_map(
            fn (self $p) => ['title' => $p->label(), 'value' => $p->value],
            $providers,
        );
    }
}
