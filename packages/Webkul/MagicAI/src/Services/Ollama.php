<?php

namespace Webkul\MagicAI\Services;

use GuzzleHttp\Client;

class Ollama
{
    /**
     * New service instance.
     */
    public function __construct(
        protected string $model,
        protected string $prompt,
        protected float $temperature,
        protected bool $stream,
        protected bool $raw,
    ) {}

    /**
     * Set LLM prompt text.
     */
    public function ask(): string
    {
        $httpClient = new Client;

        $endpoint = core()->getConfigData('general.magic_ai.settings.api_domain').'/api/generate';

        $result = $httpClient->request('POST', $endpoint, [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'json'    => [
                'model'  => $this->model,
                'prompt' => $this->prompt,
                'raw'    => $this->raw,
                'stream' => $this->stream,
            ],
        ]);

        $result = json_decode($result->getBody()->getContents(), true);

        return $result['response'];
    }
}
