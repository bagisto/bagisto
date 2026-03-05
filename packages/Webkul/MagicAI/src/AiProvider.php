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
     * Provider registry — single source of truth.
     *
     * Adding a new provider requires only one new entry here
     * and a corresponding model enum.
     *
     * Keys are Lab enum cases so the registry is tightly coupled
     * to what the Laravel AI SDK actually supports.
     *
     * @var array<int, array{provider: Lab, label: string, model_enum: class-string<AiModelContract>}>
     */
    private static array $providers = [
        ['provider' => Lab::Anthropic,  'label' => 'Anthropic',  'model_enum' => AnthropicModel::class],
        ['provider' => Lab::DeepSeek,   'label' => 'DeepSeek',   'model_enum' => DeepSeekModel::class],
        ['provider' => Lab::Gemini,     'label' => 'Gemini',     'model_enum' => GeminiModel::class],
        ['provider' => Lab::Groq,       'label' => 'Groq',       'model_enum' => GroqModel::class],
        ['provider' => Lab::Mistral,    'label' => 'Mistral',    'model_enum' => MistralModel::class],
        ['provider' => Lab::Ollama,     'label' => 'Ollama',     'model_enum' => OllamaModel::class],
        ['provider' => Lab::OpenAI,     'label' => 'OpenAI',     'model_enum' => OpenAiModel::class],
        ['provider' => Lab::xAI,        'label' => 'xAI',        'model_enum' => XAiModel::class],
    ];

    /**
     * Get the human-readable label for a provider.
     */
    public static function label(string $provider): string
    {
        return self::findProvider($provider)['label'] ?? ucfirst($provider);
    }

    /**
     * Check whether a provider is registered and supported.
     */
    public static function isProviderSupported(string $provider): bool
    {
        return self::findProvider($provider) !== null;
    }

    /**
     * Resolve a provider string to its Lab enum instance.
     *
     * Only providers registered in the $providers array can be resolved.
     */
    public static function resolve(string $provider): Lab
    {
        $entry = self::findProvider($provider);

        if (! $entry) {
            throw new \ValueError("\"{$provider}\" is not a registered AI provider.");
        }

        return $entry['provider'];
    }

    /**
     * Resolve a model identifier string to its AiModelContract enum case.
     */
    public static function resolveModel(string $model): ?AiModelContract
    {
        foreach (self::allModels() as $m) {
            if ($m->value === $model) {
                return $m;
            }
        }

        return null;
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
     * Get the recommended default text model for the given provider.
     */
    public static function defaultTextModel(string $provider): ?AiModelContract
    {
        $entry = self::findProvider($provider);

        return $entry ? $entry['model_enum']::defaultTextModel() : null;
    }

    /**
     * Get the recommended default image model for the given provider.
     */
    public static function defaultImageModel(string $provider): ?AiModelContract
    {
        $entry = self::findProvider($provider);

        return $entry ? $entry['model_enum']::defaultImageModel() : null;
    }

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

    // -------------------------------------------------------------------------
    // Provider options for config select/multiselect fields.
    // -------------------------------------------------------------------------

    /**
     * Get text providers as [{title, value}] for system config fields.
     *
     * @return array<int, array{title: string, value: string}>
     */
    public static function textProviderOptions(): array
    {
        return self::buildProviderOptions(self::textProviders());
    }

    /**
     * Get image providers as [{title, value}] for system config fields.
     *
     * @return array<int, array{title: string, value: string}>
     */
    public static function imageProviderOptions(): array
    {
        return self::buildProviderOptions(self::imageProviders());
    }

    // -------------------------------------------------------------------------
    // Model options for config select fields (all [{title, value}]).
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
     * Build a flat [{title, value}] model list from enabled providers.
     *
     * Reads the enabled provider keys from a comma-separated multiselect
     * config value and returns only models belonging to those providers,
     * prefixed with the provider label (e.g. "OpenAI: GPT-4o").
     *
     * @param  string[]  $enabledProviders  Array of provider key strings.
     * @param  'text'|'image'  $type
     * @return array<int, array{title: string, value: string}>
     */
    public static function modelsForProviders(array $enabledProviders, string $type = 'text'): array
    {
        $filter = $type === 'image'
            ? fn (AiModelContract $m) => $m->isImageModel()
            : fn (AiModelContract $m) => $m->isTextModel();

        $models = [];

        foreach ($enabledProviders as $provider) {
            if (! self::findProvider($provider)) {
                continue;
            }

            $lab = self::resolve($provider);

            foreach (self::filterModels(fn (AiModelContract $m) => $filter($m) && $m->provider() === $lab) as $m) {
                $models[] = [
                    'title' => self::label($provider).': '.$m->label(),
                    'value' => $m->value,
                ];
            }
        }

        return $models;
    }

    // -------------------------------------------------------------------------
    // Internals.
    // -------------------------------------------------------------------------

    /**
     * Get all model cases across every registered provider enum.
     *
     * Results are cached for the duration of the request.
     *
     * @return AiModelContract[]
     */
    private static function allModels(): array
    {
        return array_merge(
            ...array_map(
                fn (array $entry) => $entry['model_enum']::cases(),
                self::$providers,
            ),
        );
    }

    /**
     * Find a provider entry by its string value.
     *
     * @return array{provider: Lab, label: string, model_enum: class-string<AiModelContract>}|null
     */
    private static function findProvider(string $provider): ?array
    {
        foreach (self::$providers as $entry) {
            if ($entry['provider']->value === $provider) {
                return $entry;
            }
        }

        return null;
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
     * Build [{title, value}] options prefixed with the provider name.
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
