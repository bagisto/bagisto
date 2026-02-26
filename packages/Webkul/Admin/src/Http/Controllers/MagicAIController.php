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
            'model' => 'required',
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
        $this->validate(request(), [
            'prompt' => 'required',
            'model' => 'required|in:'.implode(',', config('magic_ai.image_models', [])),
            'n' => 'nullable|integer|min:1|max:10',
            'size' => 'required|in:1024x1024,1024x1792,1792x1024',
            'quality' => 'nullable|in:standard,hd',
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
