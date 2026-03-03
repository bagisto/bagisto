<?php

namespace Webkul\MagicAI;

use Laravel\Ai\Image;
use Webkul\MagicAI\Helpers\AiModelHelper;

use function Laravel\Ai\agent;

class MagicAI
{
    /**
     * Generate text using the content_generation feature settings.
     * Optionally accept a model override from the frontend.
     */
    public function generateContent(string $prompt, ?string $model = null): string
    {
        [$provider, $configModel] = $this->featureConfig('content_generation');

        $model = $model ?? $configModel;

        return trim(agent()->prompt($prompt, provider: $provider, model: $model)->text);
    }

    /**
     * Generate images using the image_generation feature settings.
     * Optionally accept a model override from the frontend.
     *
     * @param  array{n?: int, size?: string, quality?: string}  $options
     * @return array<int, array{url: string}>
     */
    public function generateImage(string $prompt, array $options = [], ?string $model = null): array
    {
        [$provider, $configModel] = $this->featureConfig('image_generation');

        $model = $model ?? $configModel;

        return $this->buildImages($prompt, $options, $provider, $model);
    }

    /**
     * Generate a personalised checkout success message for an order.
     * Delegates to generateContent() using the content_generation settings.
     */
    public function checkoutMessage(mixed $order): string
    {
        return $this->generateContent($this->buildCheckoutPrompt($order));
    }

    /**
     * Translate a product review into the given locale.
     * Delegates to generateContent() using the content_generation settings.
     */
    public function translateReview(mixed $review, mixed $locale): string
    {
        $prompt = "Translate the following product review to {$locale->name}. "
            .'Ensure the translation retains the original sentiment and conveys the meaning accurately. '
            ."Adapt any product-specific expressions to {$locale->name} where appropriate.\n\n"
            ."---\n\n**Original Product Review:**\n{$review->comment}\n\n---\nTranslation:";

        return $this->generateContent($prompt);
    }

    /**
     * Resolve the provider and optional model for a named feature from admin config.
     * Injects the stored API key into the runtime config so the SDK authenticates correctly.
     * Falls back to the enum's recommended default model when no model is saved.
     *
     * @return array{?string, ?string} [$provider, $model]
     */
    protected function featureConfig(string $feature): array
    {
        $provider = core()->getConfigData("general.magic_ai.{$feature}.provider");

        if ($provider && array_key_exists($provider, config('ai.providers', []))) {
            $apiKey = core()->getConfigData("general.magic_ai.{$feature}.api_key");

            if ($apiKey) {
                config(["ai.providers.{$provider}.key" => $apiKey]);
            }
        } else {
            $provider = null;
        }

        $model = core()->getConfigData("general.magic_ai.{$feature}.model") ?: null;

        if (! $model && $provider) {
            $enumDefault = $feature === 'image_generation'
                ? AiModelHelper::defaultImageModel($provider)
                : AiModelHelper::defaultTextModel($provider);

            $model = $enumDefault?->value;
        }

        return [$provider, $model];
    }

    /**
     * Shared image-generation loop used by generateImage().
     *
     * Supported sizes: 1024x1024 (square), 1792x1024 (landscape), 1024x1792 (portrait).
     * Supported quality values: 'hd' → high, 'standard' → medium.
     *
     * @param  array{n?: int, size?: string, quality?: string}  $options
     * @return array<int, array{url: string}>
     */
    protected function buildImages(string $prompt, array $options, ?string $provider, ?string $model): array
    {
        $count = max((int) ($options['n'] ?? 1), 1);
        $size = $options['size'] ?? '1024x1024';
        $quality = match ($options['quality'] ?? null) {
            'hd' => 'high',
            'standard' => 'medium',
            default => null,
        };

        $images = [];

        for ($i = 1; $i <= $count; $i++) {
            $request = match ($size) {
                '1792x1024' => Image::of($prompt)->landscape(),
                '1024x1792' => Image::of($prompt)->portrait(),
                default => Image::of($prompt)->square(),
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
     * Build the full prompt for the personalised checkout success message.
     * Reads the base prompt template from admin config and appends order context.
     */
    protected function buildCheckoutPrompt(mixed $order): string
    {
        $prompt = (string) (core()->getConfigData('general.magic_ai.default_prompts.checkout_message') ?? '');

        $productLines = '';

        foreach ($order->items as $item) {
            $productLines .= "Name: {$item->name}\n";
            $productLines .= "Qty: {$item->qty_ordered}\n";
            $productLines .= 'Price: '.core()->formatPrice($item->total)."\n\n";
        }

        $prompt .= "\n\nProduct Details:\n{$productLines}";
        $prompt .= "Customer Details:\n{$order->customer_full_name}\n\n";
        $prompt .= 'Current Locale: '.core()->getCurrentLocale()->name."\n\n";
        $prompt .= 'Store Name: '.core()->getCurrentChannel()->name;

        return $prompt;
    }
}
