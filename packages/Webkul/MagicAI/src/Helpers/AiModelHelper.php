<?php

namespace Webkul\MagicAI\Helpers;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\AiModel;

class AiModelHelper
{
    /**
     * All text models.
     *
     * @return AiModel[]
     */
    public static function textModels(): array
    {
        return self::filter(fn (AiModel $m) => $m->isTextModel());
    }

    /**
     * All image models.
     *
     * @return AiModel[]
     */
    public static function imageModels(): array
    {
        return self::filter(fn (AiModel $m) => $m->isImageModel());
    }

    /**
     * All models for a given Lab provider.
     *
     * @return AiModel[]
     */
    public static function forProvider(Lab $provider): array
    {
        return self::filter(fn (AiModel $m) => $m->provider() === $provider);
    }

    /**
     * Text models for a given provider string (e.g. 'openai').
     *
     * @return AiModel[]
     */
    public static function textModelsForProvider(string $provider): array
    {
        $lab = Lab::tryFrom($provider);

        if (! $lab) {
            return [];
        }

        return self::filter(
            fn (AiModel $m) => $m->isTextModel() && $m->provider() === $lab
        );
    }

    /**
     * Image models for a given provider string (e.g. 'openai').
     *
     * @return AiModel[]
     */
    public static function imageModelsForProvider(string $provider): array
    {
        $lab = Lab::tryFrom($provider);

        if (! $lab) {
            return [];
        }

        return self::filter(
            fn (AiModel $m) => $m->isImageModel() && $m->provider() === $lab
        );
    }

    /**
     * All text models as [{title, value}] for system config select fields.
     *
     * @return array<int, array{title: string, value: string}>
     */
    public static function textModelOptions(): array
    {
        return self::buildOptions(self::textModels());
    }

    /**
     * All image models as [{title, value}] for system config select fields.
     *
     * @return array<int, array{title: string, value: string}>
     */
    public static function imageModelOptions(): array
    {
        return self::buildOptions(self::imageModels());
    }

    /**
     * Text models for a provider as [{label, value}] for the frontend Vue dropdown.
     *
     * @return array<int, array{label: string, value: string}>
     */
    public static function textModelSelectOptions(string $provider): array
    {
        return array_map(
            fn (AiModel $m) => ['label' => $m->label(), 'value' => $m->value],
            self::textModelsForProvider($provider),
        );
    }

    /**
     * Image models for a provider as [{label, value}] for the frontend Vue dropdown.
     *
     * @return array<int, array{label: string, value: string}>
     */
    public static function imageModelSelectOptions(string $provider): array
    {
        return array_map(
            fn (AiModel $m) => ['label' => $m->label(), 'value' => $m->value],
            self::imageModelsForProvider($provider),
        );
    }

    /**
     * Recommended default text model per provider.
     */
    public static function defaultTextModel(string $provider): ?AiModel
    {
        $defaults = [
            'openai'    => AiModel::GPT4OMini,
            'anthropic' => AiModel::Claude35Haiku,
            'gemini'    => AiModel::Gemini20Flash,
            'groq'      => AiModel::GroqLlama33_70B,
            'xai'       => AiModel::Grok2Latest,
            'deepseek'  => AiModel::DeepSeekChat,
            'mistral'   => AiModel::MistralSmall,
            'ollama'    => AiModel::OllamaLlama32_3B,
        ];

        return $defaults[$provider] ?? (self::textModelsForProvider($provider)[0] ?? null);
    }

    /**
     * Recommended default image model per provider.
     */
    public static function defaultImageModel(string $provider): ?AiModel
    {
        $defaults = [
            'openai' => AiModel::DallE3,
            'gemini' => AiModel::Imagen3,
            'xai'    => AiModel::Aurora,
        ];

        return $defaults[$provider] ?? (self::imageModelsForProvider($provider)[0] ?? null);
    }

    // -------------------------------------------------------------------------
    // Internals
    // -------------------------------------------------------------------------

    /**
     * Build [{title, value}] options from an array of cases,
     * prefixed with the provider name: "OpenAI: GPT-4o".
     *
     * @param  AiModel[]  $models
     * @return array<int, array{title: string, value: string}>
     */
    private static function buildOptions(array $models): array
    {
        $providerLabels = [
            'openai'    => 'OpenAI',
            'anthropic' => 'Anthropic',
            'gemini'    => 'Gemini',
            'groq'      => 'Groq',
            'xai'       => 'xAI',
            'deepseek'  => 'DeepSeek',
            'mistral'   => 'Mistral',
            'azure'     => 'Azure OpenAI',
            'ollama'    => 'Ollama',
        ];

        return array_map(function (AiModel $m) use ($providerLabels) {
            $key  = $m->provider()->value;
            $name = $providerLabels[$key] ?? ucfirst($key);

            return [
                'title' => "$name: {$m->label()}",
                'value' => $m->value,
            ];
        }, $models);
    }

    /**
     * Filter AiModel cases by a predicate and re-index.
     *
     * @return AiModel[]
     */
    private static function filter(callable $predicate): array
    {
        return array_values(array_filter(AiModel::cases(), $predicate));
    }
}
