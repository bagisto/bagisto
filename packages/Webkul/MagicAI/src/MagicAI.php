<?php

namespace Webkul\MagicAI;

use Laravel\Ai\Image;
use Webkul\MagicAI\Helpers\AiModelHelper;

use function Laravel\Ai\agent;

class MagicAI
{
    /**
     * Generate text content from a prompt using the content_generation settings.
     */
    public function generateContent(string $prompt, ?string $model = null): string
    {
        ['provider' => $provider, 'model' => $configuredModel] = $this->loadFeatureConfig('content_generation');

        return trim(
            agent()->prompt($prompt, provider: $provider, model: $model ?? $configuredModel)->text
        );
    }

    /**
     * Generate images from a prompt using the image_generation settings.
     *
     * @param  array{n?: int, size?: string, quality?: string}  $options
     * @return array<int, array{url: string}>
     */
    public function generateImage(string $prompt, array $options = [], ?string $model = null): array
    {
        ['provider' => $provider, 'model' => $configuredModel] = $this->loadFeatureConfig('image_generation');

        return $this->executeImages($prompt, $options, $provider, $model ?? $configuredModel);
    }

    /**
     * Generate a personalised checkout success message for an order.
     */
    public function checkoutMessage(mixed $order): string
    {
        return $this->generateContent($this->buildCheckoutPrompt($order));
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

        return $this->generateContent($prompt);
    }

    /**
     * Resolve the provider and model for a named feature from admin config.
     * Injects the stored API key into the Laravel AI runtime config.
     * Falls back to the enum's recommended default when no model is saved.
     *
     * @return array{provider: ?string, model: ?string}
     */
    protected function loadFeatureConfig(string $feature): array
    {
        $configKey = "general.magic_ai.{$feature}";
        $provider  = core()->getConfigData("{$configKey}.provider");

        if ($provider && array_key_exists($provider, config('ai.providers', []))) {
            $this->injectApiKey($provider, core()->getConfigData("{$configKey}.api_key"));
        } else {
            $provider = null;
        }

        $model = core()->getConfigData("{$configKey}.model") ?: null;

        if (! $model && $provider) {
            $default = $feature === 'image_generation'
                ? AiModelHelper::defaultImageModel($provider)
                : AiModelHelper::defaultTextModel($provider);

            $model = $default?->value;
        }

        return ['provider' => $provider, 'model' => $model];
    }

    /**
     * Write the API key into the Laravel AI runtime config for the given provider.
     */
    protected function injectApiKey(string $provider, ?string $apiKey): void
    {
        if ($apiKey) {
            config(["ai.providers.{$provider}.key" => $apiKey]);
        }
    }

    /**
     * Execute image generation requests and return base64-encoded results.
     *
     * Supported sizes:   1024x1024 (square) · 1792x1024 (landscape) · 1024x1792 (portrait)
     * Supported quality: 'hd' → 'high'  |  'standard' → 'medium'
     *
     * @param  array{n?: int, size?: string, quality?: string}  $options
     * @return array<int, array{url: string}>
     */
    protected function executeImages(string $prompt, array $options, ?string $provider, ?string $model): array
    {
        $count   = max((int) ($options['n'] ?? 1), 1);
        $size    = $options['size'] ?? '1024x1024';
        $quality = $this->resolveQuality($options['quality'] ?? null);

        $images = [];

        for ($i = 0; $i < $count; $i++) {
            $request = match ($size) {
                '1792x1024' => Image::of($prompt)->landscape(),
                '1024x1792' => Image::of($prompt)->portrait(),
                default     => Image::of($prompt)->square(),
            };

            if ($quality) {
                $request->quality($quality);
            }

            $generated = $request->generate(provider: $provider, model: $model);

            $images[] = [
                'url' => 'data:'.$generated->firstImage()->mime.';base64,'.$generated->firstImage()->image,
            ];
        }

        return $images;
    }

    /**
     * Map the frontend quality string to the SDK's quality constant.
     */
    private function resolveQuality(?string $quality): ?string
    {
        return match ($quality) {
            'hd'       => 'high',
            'standard' => 'medium',
            default    => null,
        };
    }

    /**
     * Build the checkout success message prompt from config and order context.
     */
    protected function buildCheckoutPrompt(mixed $order): string
    {
        $basePrompt = (string) (core()->getConfigData('general.magic_ai.default_prompts.checkout_message') ?? '');

        $productLines = '';

        foreach ($order->items as $item) {
            $productLines .= "Name: {$item->name}\n";
            $productLines .= "Qty: {$item->qty_ordered}\n";
            $productLines .= 'Price: '.core()->formatPrice($item->total)."\n\n";
        }

        return implode("\n\n", array_filter([
            $basePrompt,
            "Product Details:\n{$productLines}",
            "Customer Details:\n{$order->customer_full_name}",
            'Current Locale: '.core()->getCurrentLocale()->name,
            'Store Name: '.core()->getCurrentChannel()->name,
        ]));
    }
}
