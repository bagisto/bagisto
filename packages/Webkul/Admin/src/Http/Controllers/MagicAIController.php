<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Webkul\Admin\Http\Requests\MagicAI\ContentGenerationRequest;
use Webkul\Admin\Http\Requests\MagicAI\ImageGenerationRequest;
use Webkul\MagicAI\Facades\MagicAI;
use Webkul\MagicAI\ProviderError;

class MagicAIController extends Controller
{
    /**
     * Generate text content from a prompt.
     */
    public function content(ContentGenerationRequest $request): JsonResponse
    {
        try {
            return new JsonResponse([
                'content' => MagicAI::generateContent(
                    $request->input('prompt'),
                    $request->input('model'),
                ),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => ProviderError::message($e)], 500);
        }
    }

    /**
     * Generate images from a prompt.
     */
    public function image(ImageGenerationRequest $request): JsonResponse
    {
        try {
            return new JsonResponse([
                'images' => MagicAI::generateImage(
                    $request->input('prompt'),
                    $request->only(['n', 'size', 'quality']),
                    $request->input('model'),
                ),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => ProviderError::message($e)], 500);
        }
    }
}
