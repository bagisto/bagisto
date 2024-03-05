<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Webkul\MagicAI\Facades\MagicAI;

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
