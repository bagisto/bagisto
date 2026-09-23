<?php

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\AiProvider;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

/**
 * The value of every provider offering at least one model of the given kind.
 */
function magicAiProviderValues(array $providers): array
{
    return array_map(fn (Lab $provider) => $provider->value, $providers);
}

// ============================================================================
// Model Registry
// ============================================================================

it('should offer each model id under a single provider', function () {
    $values = array_map(
        fn (AiModelContract $model) => $model->value,
        array_merge(AiProvider::textModels(), AiProvider::imageModels()),
    );

    expect($values)->toHaveCount(count(array_unique($values)));
});

it('should recommend a default text model the provider itself offers', function (string $provider) {
    $model = AiProvider::defaultTextModel($provider);

    expect($model)->not->toBeNull()
        ->and($model->provider()->value)->toBe($provider)
        ->and($model->isTextModel())->toBeTrue();
})->with(fn () => magicAiProviderValues(AiProvider::textProviders()));

it('should recommend a default image model the provider itself offers', function (string $provider) {
    $model = AiProvider::defaultImageModel($provider);

    expect($model)->not->toBeNull()
        ->and($model->provider()->value)->toBe($provider)
        ->and($model->isImageModel())->toBeTrue();
})->with(fn () => magicAiProviderValues(AiProvider::imageProviders()));
