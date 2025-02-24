<?php

namespace Webkul\MagicAI\Services;

use GuzzleHttp\Client;

class Gemini
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
     * Send request to Gemini AI.
     */
    public function ask(): string
    {
        $httpClient = new Client;

        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";

        $apiKey = core()->getConfigData('general.magic_ai.settings.api_key');

        $result = $httpClient->request('POST', $endpoint, [
            'headers' => [
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'Authorization' => "Bearer $apiKey",
            ],
            'json'    => [
                'contents' => [
                    ['parts' => [['text' => $this->prompt]]],
                ],
                'temperature' => $this->temperature,
            ],
        ]);

        $result = json_decode($result->getBody()->getContents(), true);

        return $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
    }
}
