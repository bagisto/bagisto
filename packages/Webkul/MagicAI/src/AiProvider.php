<?php

namespace Webkul\MagicAI;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;
use Webkul\MagicAI\Enums\Models\AnthropicModel;
use Webkul\MagicAI\Enums\Models\DeepSeekModel;
use Webkul\MagicAI\Enums\Models\GeminiModel;
use Webkul\MagicAI\Enums\Models\GroqModel;
use Webkul\MagicAI\Enums\Models\MistralModel;
use Webkul\MagicAI\Enums\Models\OllamaModel;
use Webkul\MagicAI\Enums\Models\OpenAiModel;
use Webkul\MagicAI\Enums\Models\XAiModel;

class AiProvider
{
    /**
     * Provider-to-model-enum class map.
     *
     * Adding a new provider requires only one new entry here
     * and a corresponding model enum in Enums/Models/.
     *
     * @var array<string, class-string<AiModelContract>>
     */
    private static array $modelEnums = [
        'openai' => OpenAiModel::class,
        'anthropic' => AnthropicModel::class,
        'gemini' => GeminiModel::class,
        'groq' => GroqModel::class,
        'xai' => XAiModel::class,
        'deepseek' => DeepSeekModel::class,
        'mistral' => MistralModel::class,
        'ollama' => OllamaModel::class,
    ];

    /**
     * Human-readable labels for providers.
     *
     * @var array<string, string>
     */
    private static array $labels = [
        'anthropic' => 'Anthropic',
        'azure' => 'Azure OpenAI',
        'deepseek' => 'DeepSeek',
        'gemini' => 'Gemini',
        'groq' => 'Groq',
        'mistral' => 'Mistral',
        'ollama' => 'Ollama',
        'openai' => 'OpenAI',
        'xai' => 'xAI',
    ];

    /**
     * Resolve a provider string to its Lab enum instance.
     */
    public static function resolve(string $provider): Lab
    {
        return Lab::from($provider);
    }

    /**
     * Get the default provider value for text generation features.
     */
    public static function defaultTextProvider(): string
    {
        return Lab::OpenAI->value;
    }

    /**
     * Get the default provider value for image generation features.
     */
    public static function defaultImageProvider(): string
    {
        return Lab::Gemini->value;
    }

    /**
     * Get the human-readable label for a provider.
     */
    public static function label(string $provider): string
    {
        return self::$labels[$provider] ?? ucfirst($provider);
    }

    // -------------------------------------------------------------------------
    // Provider capabilities (derived from model enums).
    // -------------------------------------------------------------------------

    /**
     * Get all providers that have at least one text model.
     *
     * @return Lab[]
     */
    public static function textProviders(): array
    {
        return self::providersWithModels(fn (AiModelContract $m) => $m->isTextModel());
    }

    /**
     * Get all providers that have at least one image model.
     *
     * @return Lab[]
     */
    public static function imageProviders(): array
    {
        return self::providersWithModels(fn (AiModelContract $m) => $m->isImageModel());
    }

    // -------------------------------------------------------------------------
    // Provider options for config select fields.
    // -------------------------------------------------------------------------

    /**
     * Get text providers as [{title, value}] for system config select fields.
     *
     * @return array<int, array{title: string, value: string}>
     */
    public static function textProviderOptions(): array
    {
        return self::buildProviderOptions(self::textProviders());
    }

    /**
     * Get image providers as [{title, value}] for system config select fields.
     *
     * @return array<int, array{title: string, value: string}>
     */
    public static function imageProviderOptions(): array
    {
        return self::buildProviderOptions(self::imageProviders());
    }

    // -------------------------------------------------------------------------
    // Model queries.
    // -------------------------------------------------------------------------

    /**
     * Get all text models across every provider.
     *
     * @return AiModelContract[]
     */
    public static function textModels(): array
    {
        return self::filterModels(fn (AiModelContract $m) => $m->isTextModel());
    }

    /**
     * Get all image models across every provider.
     *
     * @return AiModelContract[]
     */
    public static function imageModels(): array
    {
        return self::filterModels(fn (AiModelContract $m) => $m->isImageModel());
    }

    /**
     * Get all models for a given provider.
     *
     * @return AiModelContract[]
     */
    public static function forProvider(string $provider): array
    {
        $lab = self::resolve($provider);

        return self::filterModels(fn (AiModelContract $m) => $m->provider() === $lab);
    }

    /**
     * Get text models for the given provider.
     *
     * @return AiModelContract[]
     */
    public static function textModelsForProvider(string $provider): array
    {
        $lab = self::resolve($provider);

        return self::filterModels(
            fn (AiModelContract $m) => $m->isTextModel() && $m->provider() === $lab
        );
    }

    /**
     * Get image models for the given provider.
     *
     * @return AiModelContract[]
     */
    public static function imageModelsForProvider(string $provider): array
    {
        $lab = self::resolve($provider);

        return self::filterModels(
            fn (AiModelContract $m) => $m->isImageModel() && $m->provider() === $lab
        );
    }

    // -------------------------------------------------------------------------
    // Model options for config / Vue select fields.
    // -------------------------------------------------------------------------

    /**
     * Get all text models as [{title, value}] for system config select fields.
     *
     * @return array<int, array{title: string, value: string}>
     */
    public static function textModelOptions(): array
    {
        return self::buildModelOptions(self::textModels());
    }

    /**
     * Get all image models as [{title, value}] for system config select fields.
     *
     * @return array<int, array{title: string, value: string}>
     */
    public static function imageModelOptions(): array
    {
        return self::buildModelOptions(self::imageModels());
    }

    /**
     * Get text models for a provider as [{label, value}] for the frontend Vue dropdown.
     *
     * @return array<int, array{label: string, value: string}>
     */
    public static function textModelSelectOptions(string $provider): array
    {
        return array_map(
            fn (AiModelContract $m) => ['label' => $m->label(), 'value' => $m->value],
            self::textModelsForProvider($provider),
        );
    }

    /**
     * Get image models for a provider as [{label, value}] for the frontend Vue dropdown.
     *
     * @return array<int, array{label: string, value: string}>
     */
    public static function imageModelSelectOptions(string $provider): array
    {
        return array_map(
            fn (AiModelContract $m) => ['label' => $m->label(), 'value' => $m->value],
            self::imageModelsForProvider($provider),
        );
    }

    // -------------------------------------------------------------------------
    // Default models (delegated to each provider enum).
    // -------------------------------------------------------------------------

    /**
     * Get the recommended default text model for the given provider.
     */
    public static function defaultTextModel(string $provider): ?AiModelContract
    {
        $enum = self::$modelEnums[$provider] ?? null;

        return $enum ? $enum::defaultTextModel() : null;
    }

    /**
     * Get the recommended default image model for the given provider.
     */
    public static function defaultImageModel(string $provider): ?AiModelContract
    {
        $enum = self::$modelEnums[$provider] ?? null;

        return $enum ? $enum::defaultImageModel() : null;
    }

    // -------------------------------------------------------------------------
    // Internals.
    // -------------------------------------------------------------------------

    /**
     * Get all model cases across every registered provider enum.
     *
     * @return AiModelContract[]
     */
    private static function allModels(): array
    {
        return array_merge(
            ...array_values(array_map(
                fn (string $enum) => $enum::cases(),
                self::$modelEnums,
            )),
        );
    }

    /**
     * Get unique providers that have at least one model matching the predicate.
     *
     * @return Lab[]
     */
    private static function providersWithModels(callable $predicate): array
    {
        $seen = [];

        foreach (self::allModels() as $model) {
            if ($predicate($model)) {
                $seen[$model->provider()->value] = true;
            }
        }

        return array_values(array_filter(
            Lab::cases(),
            fn (Lab $p) => isset($seen[$p->value]),
        ));
    }

    /**
     * Build [{title, value}] options for provider select fields.
     *
     * @param  Lab[]  $providers
     * @return array<int, array{title: string, value: string}>
     */
    private static function buildProviderOptions(array $providers): array
    {
        return array_map(
            fn (Lab $p) => ['title' => self::label($p->value), 'value' => $p->value],
            $providers,
        );
    }

    /**
     * Build [{title, value}] options prefixed with the provider name (e.g. "OpenAI: GPT-4o").
     *
     * @param  AiModelContract[]  $models
     * @return array<int, array{title: string, value: string}>
     */
    private static function buildModelOptions(array $models): array
    {
        return array_map(fn (AiModelContract $model) => [
            'title' => self::label($model->provider()->value).': '.$model->label(),
            'value' => $model->value,
        ], $models);
    }

    /**
     * Filter all model cases by a predicate and re-index.
     *
     * @return AiModelContract[]
     */
    private static function filterModels(callable $predicate): array
    {
        return array_values(array_filter(self::allModels(), $predicate));
    }
}
