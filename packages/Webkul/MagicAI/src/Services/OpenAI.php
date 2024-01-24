<?php

namespace Webkul\MagicAI\Services;

use Illuminate\Support\Arr;
use OpenAI as TempOpenAI;
use OpenAI\Laravel\Facades\OpenAI as BaseOpenAI;

class OpenAI
{
    /**
     * New service instance.
     */
    public function __construct(
        protected string $model,
        protected string $prompt,
        protected float $temperature,
        protected bool $stream = false
    ) {
        $this->setConfig();
    }

    /**
     * Sets OpenAI credentials.
     */
    public function setConfig(): void
    {
        config([
            'openai.api_key'      => core()->getConfigData('general.magic_ai.settings.api_key'),
            'openai.organization' => core()->getConfigData('general.magic_ai.settings.organization'),
        ]);
    }

    /**
     * Set LLM prompt text.
     */
    public function ask(): string
    {
        $client = TempOpenAI::factory()
            ->withBaseUri('http://192.168.15.22:1234/v1') // default: api.openai.com/v1
            // ->withStreamHandler(fn ($request) => $client->send($request, [
            //     'stream' => true // Allows to provide a custom stream handler for the http client.
            // ]))
            ->make();

        // $response = $client->completions()->createStreamed([
        //     'model'       => $this->model,
        //     'temperature' => $this->temperature,
        //     'messages'    => [
        //         [
        //             'role'    => 'user',
        //             'content' => $this->prompt,
        //         ],
        //     ],
        // ]);

        // $stream = $client->chat()->createStreamed([
        //     'model'      => $this->model,
        //     'messages'    => [
        //         [
        //             'role'    => 'user',
        //             'content' => $this->prompt,
        //         ],
        //     ],
        // ]);

        $stream = $client->chat()->createStreamed([
            'model'    => $this->model,
            'messages' => [
                [
                    'role'    => 'user',
                    'content' => $this->prompt,
                ],
            ],
        ]);

        foreach ($stream as $response) {
            $choice = Arr::first($response->choices);

            if (empty($choice->delta->content)) {
                continue;
            }

            echo $choice->delta->content;
        }

        exit;

        $result = BaseOpenAI::chat()->create([
            'model'       => $this->model,
            'temperature' => $this->temperature,
            'messages'    => [
                [
                    'role'    => 'user',
                    'content' => $this->prompt,
                ],
            ],
        ]);

        return $result->choices[0]->message->content;
    }

    /**
     * Generate image.
     */
    public function images(array $options): array
    {
        $result = BaseOpenAI::images()->create([
            'model'           => $this->model,
            'prompt'          => $this->prompt,
            'n'               => intval($options['n'] ?? 1),
            'size'            => $options['size'],
            'quality'         => $options['quality'] ?? 'standard',
            'response_format' => 'b64_json',
        ]);

        $images = [];

        foreach ($result->data as $image) {
            $images[]['url'] = 'data:image/png;base64,' . $image->b64_json;
        }

        return $images;
    }
}
