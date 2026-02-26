<?php

namespace Webkul\MagicAI;

use Laravel\Ai\Ai;
use Laravel\Ai\Image;
use Laravel\Ai\PendingResponses\PendingImageGeneration;
use RuntimeException;

use function Laravel\Ai\agent;

class MagicAI
{
    /**
     * LLM model.
     */
    protected string $model;

    /**
     * LLM agent.
     */
    protected string $agent;

    /**
     * Stream Response.
     */
    protected bool $stream = false;

    /**
     * Raw Response.
     */
    protected bool $raw = true;

    /**
     * Raw Response.
     */
    protected float $temperature = 0.7;

    /**
     * LLM prompt text.
     */
    protected string $prompt;

    /**
     * Set LLM model
     */
    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Set LLM agent
     */
    public function setAgent(string $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * Set stream response.
     */
    public function setStream(bool $stream): self
    {
        $this->stream = $stream;

        return $this;
    }

    /**
     * Set response raw.
     */
    public function setRaw(bool $raw): self
    {
        $this->raw = $raw;

        return $this;
    }

    /**
     * Set LLM prompt text.
     */
    public function setTemperature(float $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    /**
     * Set LLM prompt text.
     */
    public function setPrompt(string $prompt): self
    {
        $this->prompt = $prompt;

        return $this;
    }

    /**
     * Set LLM prompt text.
     */
    public function ask(): string
    {
        $this->ensureAiSdkIsInstalled();

        $provider = $this->resolveProvider();

        $this->configureProviderCredentials($provider);

        $response = agent()->prompt(
            $this->prompt,
            provider: $provider,
            model: $this->model
        );

        return trim($response->text);
    }

    /**
     * Generate Images
     */
    public function images(array $options): array
    {
        $this->ensureAiSdkIsInstalled();

        $provider = $this->resolveProvider();

        $this->configureProviderCredentials($provider);

        $numberOfImages = max((int) ($options['n'] ?? 1), 1);

        $images = [];

        for ($i = 1; $i <= $numberOfImages; $i++) {
            $generatedImage = $this->buildImageRequest($options)->generate(
                provider: $provider,
                model: $this->model
            );

            $images[] = [
                'url' => 'data:'.$generatedImage->firstImage()->mime.';base64,'.$generatedImage->firstImage()->image,
            ];

        }

        return $images;
    }

    /**
     * Resolve provider for selected model.
     */
    protected function resolveProvider(): ?string
    {
        $configuredProvider = config('magic_ai.model_provider_map', [])[$this->model] ?? null;

        if ($configuredProvider) {
            return $configuredProvider;
        }

        foreach (config('magic_ai.model_provider_prefix_map', []) as $provider => $prefixes) {
            foreach ($prefixes as $prefix) {
                if (str_starts_with($this->model, $prefix)) {
                    return $provider;
                }
            }
        }

        if (str_contains($this->model, ':') || str_contains($this->model, '.')) {
            return 'ollama';
        }

        return config('ai.default');
    }

    /**
     * Configure AI provider credentials from admin settings.
     */
    protected function configureProviderCredentials(?string $provider): void
    {
        $apiKey = core()->getConfigData('general.magic_ai.settings.api_key') ?: null;
        $organization = core()->getConfigData('general.magic_ai.settings.organization');
        $apiDomain = core()->getConfigData('general.magic_ai.settings.api_domain');

        $config = [
            'ai.default' => $provider ?: config('ai.default'),
            'ai.providers.anthropic.key' => config('ai.providers.anthropic.key'),
            'ai.providers.cohere.key' => config('ai.providers.cohere.key'),
            'ai.providers.eleven.key' => config('ai.providers.eleven.key'),
            'ai.providers.gemini.key' => config('ai.providers.gemini.key'),
            'ai.providers.groq.key' => config('ai.providers.groq.key'),
            'ai.providers.jina.key' => config('ai.providers.jina.key'),
            'ai.providers.mistral.key' => config('ai.providers.mistral.key'),
            'ai.providers.ollama.key' => config('ai.providers.ollama.key'),
            'ai.providers.openai.key' => config('ai.providers.openai.key'),
            'ai.providers.voyageai.key' => config('ai.providers.voyageai.key'),
            'ai.providers.xai.key' => config('ai.providers.xai.key'),
            'ai.providers.ollama.url' => $apiDomain ?: config('ai.providers.ollama.url'),
        ];

        if ($provider && $apiKey) {
            $keyPath = match ($provider) {
                'anthropic' => 'ai.providers.anthropic.key',
                'cohere' => 'ai.providers.cohere.key',
                'eleven' => 'ai.providers.eleven.key',
                'gemini' => 'ai.providers.gemini.key',
                'groq' => 'ai.providers.groq.key',
                'jina' => 'ai.providers.jina.key',
                'mistral' => 'ai.providers.mistral.key',
                'ollama' => 'ai.providers.ollama.key',
                'openai' => 'ai.providers.openai.key',
                'voyageai' => 'ai.providers.voyageai.key',
                'xai' => 'ai.providers.xai.key',
                default => null,
            };

            if ($keyPath) {
                $config[$keyPath] = $apiKey;
            }
        }

        if ($organization && $provider === 'openai') {
            $config['ai.providers.openai.organization'] = $organization;
        }

        config($config);
    }

    /**
     * Build image request.
     */
    protected function buildImageRequest(array $options): PendingImageGeneration
    {
        $request = Image::of($this->prompt);

        $size = $options['size'] ?? '1024x1024';

        if ($size === '1792x1024') {
            $request->landscape();
        } elseif ($size === '1024x1792') {
            $request->portrait();
        } else {
            $request->square();
        }

        $quality = match ($options['quality'] ?? null) {
            'hd' => 'high',
            'standard' => 'medium',
            default => null,
        };

        if ($quality) {
            $request->quality($quality);
        }

        return $request;
    }

    /**
     * Ensure Laravel AI SDK is available.
     */
    protected function ensureAiSdkIsInstalled(): void
    {
        if (! class_exists(Ai::class) || ! class_exists(Image::class) || ! function_exists('Laravel\\Ai\\agent')) {
            throw new RuntimeException('Laravel AI SDK is not installed. Please run: composer require laravel/ai');
        }
    }
}
