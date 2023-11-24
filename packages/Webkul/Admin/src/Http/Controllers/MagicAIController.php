<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use OpenAI\Laravel\Facades\OpenAI;

class MagicAIController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function generate(): JsonResponse
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
}
