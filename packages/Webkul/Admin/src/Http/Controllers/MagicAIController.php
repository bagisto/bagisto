<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Webkul\MagicAI\Facades\MagicAI;

class MagicAIController extends Controller
{
    /**
     * Generate text content from a prompt.
     */
    public function content(): JsonResponse
    {
        $this->validate(request(), [
            'prompt' => 'required',
        ]);

        try {
            return new JsonResponse([
                'content' => MagicAI::generateContent(request()->input('prompt')),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Generate images from a prompt.
     */
    public function image(): JsonResponse
    {
        $this->validate(request(), [
            'prompt' => 'required',
            'n' => 'nullable|integer|min:1|max:10',
            'size' => 'required|in:1024x1024,1024x1792,1792x1024',
            'quality' => 'nullable|in:standard,hd',
        ]);

        try {
            return new JsonResponse([
                'images' => MagicAI::generateImage(
                    request()->input('prompt'),
                    request()->only(['n', 'size', 'quality']),
                ),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }
    }
}
