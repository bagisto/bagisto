<?php

namespace Webkul\MagicAI\Services;

use Illuminate\Support\Facades\Http;

class GroqAI
{
    /**
     * Summary of API_URL
     */
    private const API_URL = 'https://api.groq.com/openai/v1/chat/completions';

    /**
     * Summary of TEMPERATURE
     */
    private const TEMPERATURE = 0.7;

    /**
     * New service instance.
     */
    public function __construct(
        protected string $model,
        protected string $prompt,
        protected float $temperature = self::TEMPERATURE,
        protected bool $stream = false
    ) {
        $this->setConfig();
    }

    /**
     * Sets Groq API credentials.
     */
    public function setConfig(): void
    {
        config([
            'groq.api_key' => core()->getConfigData('general.magic_ai.settings.api_key'),
        ]);
    }

    /**
     * Send request to Groq API.
     */
    public function ask(): string
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.config('groq.api_key'),
                'Content-Type'  => 'application/json',
            ])->post(self::API_URL, [
                'model'       => $this->model,
                'temperature' => $this->temperature,
                'messages'    => [
                    [
                        'role'    => 'user',
                        'content' => $this->prompt,
                    ],
                ],
            ]);

            $result = $response->json();

            return $result['choices'][0]['message']['content'] ?? '';
        } catch (\Exception $e) {
            return 'Exception: '.$e->getMessage();
        }
    }
}
