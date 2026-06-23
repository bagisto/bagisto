<?php

namespace Webkul\MagicAI;

use Laravel\Ai\Files\LocalImage;
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
        $provider = $this->prepareProvider($model);

        return trim(
            agent()->prompt($prompt, provider: $provider, model: $model)->text
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
        $provider = $this->prepareProvider($model);

        return $this->executeImages($prompt, $options, $provider, $model);
    }

    /**
     * Analyze an uploaded image and return e-commerce search keywords.
     *
     * @return string Comma-separated keywords suitable for product search.
     */
    public function analyzeImage(string $imagePath): string
    {
        $prompt = implode("\n\n", [
            'Analyze this image and identify the product(s) shown.',
            'Return a comma-separated list of short, specific search keywords that would help find this product in an e-commerce store.',
            'Focus on: product type, material, color, style, brand (if visible), and key features.',
            'Return ONLY the comma-separated keywords, nothing else.',
            'Example: red cotton t-shirt, casual wear, crew neck, solid color',
        ]);

        $model = $this->loadStorefrontModel('image_search');

        $provider = $this->prepareProvider($model);

        $image = new LocalImage($imagePath);

        return trim(
            agent()->prompt($prompt, attachments: [$image], provider: $provider, model: $model)->text
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

        $provider = $this->prepareProvider($model);

        return trim(
            agent()->prompt($prompt, provider: $provider, model: $model)->text
        );
    }

    /**
     * Generate a personalised checkout success message for an order.
     */
    public function checkoutMessage(mixed $order): string
    {
        $model = $this->loadStorefrontModel('checkout_message');

        $provider = $this->prepareProvider($model);

        return trim(
            agent()->prompt($this->buildCheckoutPrompt($order), provider: $provider, model: $model)->text
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
     * Resolve the provider from a model, validate it, and inject the stored API key.
     *
     * Model-first flow:
     *   1. Resolve the model string to its AiModelContract enum case.
     *   2. Derive the provider from the model.
     *   3. Verify the provider is supported by the Laravel AI SDK.
     *   4. Inject the Bagisto-stored API key into Laravel AI configuration.
     *   5. Return the provider string so callers can pass it to agent()/Image.
     */
    protected function prepareProvider(?string $model): ?string
    {
        if (! $model) {
            return null;
        }

        $modelEnum = AiProvider::resolveModel($model);

        if (! $modelEnum) {
            return null;
        }

        $provider = $modelEnum->provider()->value;

        if (! AiProvider::isProviderSupported($provider)) {
            throw new \RuntimeException("AI provider [{$provider}] is not supported by the Laravel AI SDK.");
        }

        $apiKey = core()->getConfigData("magic_ai.providers.{$provider}.api_key");

        if ($apiKey) {
            config(["ai.providers.{$provider}.key" => $apiKey]);
        }

        if ($provider === 'ollama') {
            $url = core()->getConfigData('magic_ai.providers.ollama.url');

            if ($url) {
                config(['ai.providers.ollama.url' => $url]);
            }
        }

        return $provider;
    }

    /**
     * Execute image generation requests and return base64-encoded results.
     *
     * Supported aspect ratios: 1:1 (square) · 3:2 (landscape) · 2:3 (portrait)
     *
     * Supported quality: 'high' · 'medium' · 'low'
     *
     * For providers that do not handle size/quality via API parameters
     * (e.g. xAI), these requirements are embedded directly into the prompt.
     *
     * @param  array{n?: int, size?: string, quality?: string}  $options
     * @return array<int, array{url: string}>
     */
    protected function executeImages(string $prompt, array $options, ?string $provider, ?string $model): array
    {
        $count = max((int) ($options['n'] ?? 1), 1);

        $aspectRatio = $this->resolveAspectRatio($options['size'] ?? null);

        $quality = $this->resolveQuality($options['quality'] ?? null);

        // For providers that don't handle size/quality via API parameters,
        // encode these requirements directly into the prompt.
        $imagePrompt = $this->supportsNativeImageOptions($provider)
            ? $prompt
            : $this->enrichImagePrompt($prompt, $aspectRatio, $quality);

        $images = [];

        for ($i = 0; $i < $count; $i++) {
            $request = Image::of($imagePrompt)->size($aspectRatio);

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
     * Map the frontend size value to an SDK aspect ratio.
     */
    protected function resolveAspectRatio(?string $size): string
    {
        return match ($size) {
            '3:2' => '3:2',
            '2:3' => '2:3',
            default => '1:1',
        };
    }

    /**
     * Map the frontend quality string to the SDK's quality constant.
     */
    protected function resolveQuality(?string $quality): ?string
    {
        return match ($quality) {
            'high' => 'high',
            'medium' => 'medium',
            'low' => 'low',
            default => null,
        };
    }

    /**
     * Check whether the given provider handles size/quality via API parameters.
     *
     * Providers that return empty from defaultImageOptions() (e.g. xAI)
     * do not support these options natively, so we embed them in the prompt.
     */
    protected function supportsNativeImageOptions(?string $provider): bool
    {
        return in_array($provider, ['openai', 'gemini']);
    }

    /**
     * Enrich the image prompt with size and quality requirements.
     *
     * Used for providers that do not handle these options natively.
     */
    protected function enrichImagePrompt(string $prompt, string $aspectRatio, ?string $quality): string
    {
        $orientation = match ($aspectRatio) {
            '3:2' => 'landscape orientation (wider than tall)',
            '2:3' => 'portrait orientation (taller than wide)',
            default => 'square format (equal width and height)',
        };

        $hints = [$orientation];

        if ($quality) {
            $qualityHint = match ($quality) {
                'high' => 'high detail and resolution',
                'medium' => 'standard detail and resolution',
                'low' => 'basic detail, quick draft quality',
                default => null,
            };

            if ($qualityHint) {
                $hints[] = $qualityHint;
            }
        }

        return $prompt."\n\nImage requirements: ".implode(', ', $hints).'.';
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
            'Return ONLY plain text. Do not use Markdown, HTML, bold, italic, headings, bullet points, or any formatting syntax.',
            "Product Details:\n{$productLines}",
            "Customer Details:\n{$order->customer_full_name}",
            'Current Locale: '.core()->getCurrentLocale()->name,
            'Store Name: '.core()->getCurrentChannel()->name,
        ]);
    }
}
