<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use OpenAI\Laravel\Facades\OpenAI;

class MagicAIController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function content(): JsonResponse
    {
        config([
            'openai.api_key'      => core()->getConfigData('general.magic_ai.settings.api_key'),
            'openai.organization' => core()->getConfigData('general.magic_ai.settings.organization'),
        ]);

        $this->validate(request(), [
            'prompt' => 'required',
        ]);

        try {
            $result = OpenAI::chat()->create([
                'model'    => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role'    => 'user',
                        'content' => request()->input('prompt'),
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }

        return new JsonResponse([
            'content' => $result->choices[0]->message->content,
        ]);
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
