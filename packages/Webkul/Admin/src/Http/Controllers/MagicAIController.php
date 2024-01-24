<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Webkul\MagicAI\Facades\MagicAI;

class MagicAIController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function content()
    {
        // $streamingData = ['Hello', 'World', 'How', 'Are', 'You', 'Today?'];

        // $callback = function () use ($streamingData) {
        //     foreach ($streamingData as $chunk) {
        //         echo "data: $chunk\n\n";

        //         ob_flush();
        //         flush();

        //         usleep(1000000);
        //     }
        // };

        // return response()->stream($callback, 200, [
        //     'Content-Type' => 'text/event-stream',
        //     'Cache-Control' => 'no-cache',
        //     'Connection' => 'keep-alive',
        // ]);

        // $this->validate(request(), [
        //     'model'  => 'required',
        //     'prompt' => 'required',
        // ]);

        try {
            $httpClient = new \GuzzleHttp\Client();

            $endpoint = core()->getConfigData('general.magic_ai.settings.api_domain') . '/api/generate';

            $request = new \GuzzleHttp\Psr7\Request(
                'POST',
                $endpoint,
                [
                    'Content-Type' => 'application/x-ndjson',
                ],
                json_encode([
                    'model'  => 'mistral',
                    'prompt' => 'As an E-Commerce store content writer, your objective is to create an engaging short description for the following product, highlighting its key features:\n\nProduct Name: Test Product Name\n\nFeatures:\n1: Feature 1\n2: Feature 2',
                    'stream' => true,
                    // "options" => [
                    //     "num_ctx" => 1024
                    // ]
                ])
            );

            // Stream the response
            try {
                $response = $httpClient->send($request, ['stream' => true]);

                // Process the streamed response
                $body = $response->getBody();

                while (! $body->eof()) {
                    \Log::info($body->read(250));
                }
            } catch (\Exception $e) {
                \Log::info($e->getMessage());
            }

            $callback = function () use ($stream) {
                foreach ($stream as $response) {
                    \Log::info($response->response);

                    echo "data: {$choice->response}\n\n";

                    ob_flush();

                    flush();
                }
            };

            // return response()->stream($callback, 200, [
            //     'Content-Type' => 'text/event-stream',
            //     'Cache-Control' => 'no-cache',
            //     'Connection' => 'keep-alive',
            // ]);

            $client = \OpenAI::factory()
                ->withBaseUri('http://192.168.15.22:1234/v1')
                ->make();

            $stream = $client->chat()->createStreamed([
                'messages' => [
                    [
                        'role'    => 'user',
                        'content' => 'As an E-Commerce store content writer, your objective is to create an engaging short description for the following product, highlighting its key features:\n\nProduct Name: Test Product Name\n\nFeatures:\n1: Feature 1\n2: Feature 2',
                    ],
                ],
            ]);

            $callback = function () use ($stream) {
                foreach ($stream as $response) {
                    $choice = \Illuminate\Support\Arr::first($response->choices);

                    if (empty($choice->delta->content)) {
                        continue;
                    }

                    \Log::info($choice->delta->content);

                    echo "data: {$choice->delta->content}\n\n";

                    ob_flush();

                    flush();
                }
            };

            return response()->stream($callback, 200, [
                'Content-Type'  => 'text/event-stream',
                'Cache-Control' => 'no-cache',
                'Connection'    => 'keep-alive',
            ]);

            $response = MagicAI::setModel(request()->input('model'))
                ->setPrompt(request()->input('prompt'))
                ->ask();

            return new JsonResponse([
                'content' => $response,
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function image(): JsonResponse
    {
        config([
            'openai.api_key'      => core()->getConfigData('general.magic_ai.settings.api_key'),
            'openai.organization' => core()->getConfigData('general.magic_ai.settings.organization'),
        ]);

        $this->validate(request(), [
            'prompt'  => 'required',
            'model'   => 'required|in:dall-e-2,dall-e-3',
            'n'       => 'required_if:model,dall-e-2|integer|min:1|max:10',
            'size'    => 'required|in:1024x1024,1024x1792,1792x1024',
            'quality' => 'required_if:model,dall-e-3|in:standard,hd',
        ]);

        try {
            $options = request()->only([
                'n',
                'size',
                'quality',
            ]);

            $images = MagicAI::setModel(request()->input('model'))
                ->setPrompt(request()->input('prompt'))
                ->images($options);

            return new JsonResponse([
                'images' => $images,
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
