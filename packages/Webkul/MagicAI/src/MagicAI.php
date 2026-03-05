<?php

namespace Webkul\MagicAI;

use Laravel\Ai\Image;

use function Laravel\Ai\agent;

class MagicAI
{
    /**
     * Generate text content from a prompt.
     *
     * The provider is resolved automatically from the model name.
     */
    public function generateContent(string $prompt, ?string $model = null): string
    {
        $this->injectApiKeyForModel($model);

        return trim(
            agent()->prompt($prompt, model: $model)->text
        );
    }

    /**
     * Generate images from a prompt.
     *
     * The provider is resolved automatically from the model name.
     *
     * @param  array{n?: int, size?: string, quality?: string}  $options
     * @return array<int, array{url: string}>
     */
    public function generateImage(string $prompt, array $options = [], ?string $model = null): array
    {
        $this->injectApiKeyForModel($model);

        return $this->executeImages($prompt, $options, $model);
    }

    /**
     * Generate a personalised checkout success message for an order.
     */
    public function checkoutMessage(mixed $order): string
    {
        $model = $this->loadStorefrontModel('checkout_message');

        $this->injectApiKeyForModel($model);

        return trim(
            agent()->prompt($this->buildCheckoutPrompt($order), model: $model)->text
        );
    }

    /**
     * Translate any text content into the given locale.
     */
    public function translate(string $content, string $locale): string
    {
        $prompt = implode("\n\n", [
            "Translate the following text to {$locale}.",
            'Ensure the translation retains the original sentiment and conveys the meaning accurately.',
            "Adapt any context-specific expressions to {$locale} where appropriate.",
            "---\n{$content}\n---",
            'Translation:',
        ]);

        $model = $this->loadStorefrontModel('review_translation');

        $this->injectApiKeyForModel($model);

        return trim(
            agent()->prompt($prompt, model: $model)->text
        );
    }

    /**
     * Resolve the model for a named storefront feature from admin config.
     *
     * Falls back to the enum's recommended default when no model is saved.
     */
    protected function loadStorefrontModel(string $feature): ?string
    {
        $model = core()->getConfigData("magic_ai.storefront_features.{$feature}.model") ?: null;

        if (! $model) {
            $default = AiProvider::defaultTextModel(AiProvider::defaultTextProvider());

            $model = $default?->value;
        }

        return $model;
    }

    /**
     * Resolve the provider from a model identifier and inject the stored API key.
     */
    protected function injectApiKeyForModel(?string $model): void
    {
        if (! $model) {
            return;
        }

        $provider = AiProvider::resolveProviderForModel($model);

        if ($provider) {
            $apiKey = core()->getConfigData("magic_ai.providers.{$provider}.api_key");

            if ($apiKey) {
                config(["ai.providers.{$provider}.key" => $apiKey]);
            }
        }
    }

    /**
     * Execute image generation requests and return base64-encoded results.
     *
     * Supported sizes:   1024x1024 (square) · 1792x1024 (landscape) · 1024x1792 (portrait)
     *
     * Supported quality: 'hd' → 'high'  |  'standard' → 'medium'
     *
     * @param  array{n?: int, size?: string, quality?: string}  $options
     * @return array<int, array{url: string}>
     */
    protected function executeImages(string $prompt, array $options, ?string $model): array
    {
        $count = max((int) ($options['n'] ?? 1), 1);

        $size = $options['size'] ?? '1024x1024';

        $quality = $this->resolveQuality($options['quality'] ?? null);

        $images = [];

        for ($i = 0; $i < $count; $i++) {
            $request = match ($size) {
                '1792x1024' => Image::of($prompt)->landscape(),
                '1024x1792' => Image::of($prompt)->portrait(),
                default => Image::of($prompt)->square(),
            };

            if ($quality) {
                $request->quality($quality);
            }

            $generated = $request->generate(model: $model);

            $images[] = [
                'url' => 'data:'.$generated->firstImage()->mime.';base64,'.$generated->firstImage()->image,
            ];
        }

        return $images;
    }

    /**
     * Map the frontend quality string to the SDK's quality constant.
     */
    protected function resolveQuality(?string $quality): ?string
    {
        return match ($quality) {
            'hd' => 'high',
            'standard' => 'medium',
            default => null,
        };
    }

    /**
     * Build the checkout success message prompt from order context.
     */
    protected function buildCheckoutPrompt(mixed $order): string
    {
        $productLines = '';

        foreach ($order->items as $item) {
            $productLines .= "Name: {$item->name}\n";
            $productLines .= "Qty: {$item->qty_ordered}\n";
            $productLines .= 'Price: '.core()->formatPrice($item->total)."\n\n";
        }

        return implode("\n\n", [
            'Generate a personalized checkout success message for the customer.',
            "Product Details:\n{$productLines}",
            "Customer Details:\n{$order->customer_full_name}",
            'Current Locale: '.core()->getCurrentLocale()->name,
            'Store Name: '.core()->getCurrentChannel()->name,
        ]);
    }
}
