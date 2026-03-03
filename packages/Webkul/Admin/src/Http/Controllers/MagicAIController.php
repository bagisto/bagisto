<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Webkul\MagicAI\Facades\MagicAI;

class MagicAIController extends Controller
{
    /**
     * Generate text content from a prompt.
     * Accepts an optional model override from the frontend.
     */
    public function content(): JsonResponse
    {
        $this->validate(request(), [
            'prompt' => 'required',
            'model'  => 'nullable|string',
        ]);

        try {
            return new JsonResponse([
                'content' => MagicAI::generateContent(
                    request()->input('prompt'),
                    request()->input('model'),
                ),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Generate images from a prompt.
     * Accepts an optional model override from the frontend.
     */
    public function image(): JsonResponse
    {
        $this->validate(request(), [
            'prompt'  => 'required',
            'model'   => 'nullable|string',
            'n'       => 'nullable|integer|min:1|max:10',
            'size'    => 'required|in:1024x1024,1024x1792,1792x1024',
            'quality' => 'nullable|in:standard,hd',
        ]);

        try {
            return new JsonResponse([
                'images' => MagicAI::generateImage(
                    request()->input('prompt'),
                    request()->only(['n', 'size', 'quality']),
                    request()->input('model'),
                ),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }
    }
}

