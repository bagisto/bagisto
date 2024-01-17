<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use OpenAI\Laravel\Facades\OpenAI;
use GuzzleHttp\Client;

class MagicAIController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function content(): JsonResponse
    {
        $this->validate(request(), [
            'model'  => 'required',
            'prompt' => 'required',
        ]);

        try {
            if (request()->input('model') == 'gpt-3.5-turbo') {
                config([
                    'openai.api_key'      => core()->getConfigData('general.magic_ai.settings.api_key'),
                    'openai.organization' => core()->getConfigData('general.magic_ai.settings.organization'),
                ]);

                $result = OpenAI::chat()->create([
                    'model'    => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role'    => 'user',
                            'content' => request()->input('prompt'),
                        ],
                    ],
                ]);

                $response = $result->choices[0]->message->content;
            } else {
                $httpClient = new Client();

                $endpoint = core()->getConfigData('general.magic_ai.settings.api_domain') . '/api/generate';

                $result = $httpClient->request('POST', $endpoint, [
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                    'json'    => [
                        'model'  => request()->input('model'),
                        'prompt' => request()->input('prompt'),
                        'raw'    => true,
                        'stream' => false,
                    ],
                ]);

                $result = json_decode($result->getBody()->getContents(), true);

                $response = $result['response'];
            }

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
            $result = OpenAI::images()->create([
                'model'           => request()->input('model'),
                'prompt'          => request()->input('prompt'),
                'n'               => intval(request()->input('n') ?? 1),
                'size'            => request()->input('size'),
                'quality'         => request()->input('quality') ?? 'standard',
                'response_format' => 'b64_json',
            ]);

            $images = [];

            foreach ($result->data as $image) {
                $images[]['url'] = 'data:image/png;base64,' . $image->b64_json;
            }

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
