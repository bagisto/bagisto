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
        $this->validate(request(), [
            'prompt' => 'required',
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

        return new JsonResponse([
            'content' => $result->choices[0]->message->content,
        ]);
    }
}
