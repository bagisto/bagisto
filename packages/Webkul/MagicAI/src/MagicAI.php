<?php

namespace Webkul\MagicAI;

use Laravel\Ai\Image;

use function Laravel\Ai\agent;

class MagicAI
{
    /**
     * LLM model.
     */
    protected string $model;

    /**
     * LLM provider.
     */
    protected ?string $provider = null;

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
     * Set LLM provider.
     */
    public function setProvider(?string $provider): self
    {
        $this->provider = $provider;

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
        $response = agent()->prompt($this->prompt, provider: $this->resolveProvider());

        return trim($response->text);
    }

    /**
     * Generate Images
     */
    public function images(array $options): array
    {
        $numberOfImages = max((int) ($options['n'] ?? 1), 1);

        $size = $options['size'] ?? '1024x1024';
        $quality = match ($options['quality'] ?? null) {
            'hd' => 'high',
            'standard' => 'medium',
            default => null,
        };

        $images = [];

        for ($i = 1; $i <= $numberOfImages; $i++) {
            $request = Image::of($this->prompt);

            if ($size === '1792x1024') {
                $request->landscape();
            } elseif ($size === '1024x1792') {
                $request->portrait();
            } else {
                $request->square();
            }

            if ($quality) {
                $request->quality($quality);
            }

            $generatedImage = $request->generate(provider: $this->resolveProvider());

            $images[] = [
                'url' => 'data:'.$generatedImage->firstImage()->mime.';base64,'.$generatedImage->firstImage()->image,
            ];
        }

        return $images;
    }

    /**
     * Resolve provider to an SDK configured provider key.
     */
    protected function resolveProvider(): ?string
    {
        if (! $this->provider) {
            return null;
        }

        return array_key_exists($this->provider, config('ai.providers', []))
            ? $this->provider
            : null;
    }
}
