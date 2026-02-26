<?php

return [
    'providers' => [
        'openai' => [],
        'anthropic' => [],
        'cohere' => [],
        'eleven' => [],
        'gemini' => [],
        'groq' => [],
        'jina' => [],
        'mistral' => [],
        'ollama' => [],
        'voyageai' => [],
        'xai' => [],
    ],

    'model_provider_map' => [
        'gpt-4-turbo' => 'openai',
        'gpt-4o' => 'openai',
        'gpt-4o-mini' => 'openai',
        'dall-e-2' => 'openai',
        'dall-e-3' => 'openai',
        'gemini-2.0-flash' => 'gemini',
        'llama3-8b-8192' => 'groq',
        'deepseek-r1:8b' => 'ollama',
        'llama3.3' => 'ollama',
        'llama3.2:3b' => 'ollama',
        'llama3.2:1b' => 'ollama',
        'llama3.1:8b' => 'ollama',
        'llama3:8b' => 'ollama',
        'llava:7b' => 'ollama',
        'vicuna:13b' => 'ollama',
        'vicuna:7b' => 'ollama',
        'qwen2.5:14b' => 'ollama',
        'qwen2.5:7b' => 'ollama',
        'qwen2.5:3b' => 'ollama',
        'qwen2.5:1.5b' => 'ollama',
        'qwen2.5:0.5b' => 'ollama',
        'mistral:7b' => 'ollama',
        'starling-lm:7b' => 'ollama',
        'phi3.5' => 'ollama',
        'orca-mini' => 'ollama',
    ],

    'model_provider_prefix_map' => [
        'openai' => [
            'gpt-',
            'o1',
            'o3',
            'dall-e',
        ],
        'anthropic' => [
            'claude-',
        ],
        'gemini' => [
            'gemini-',
        ],
        'xai' => [
            'grok-',
        ],
        'groq' => [
            'openai/gpt-oss-',
        ],
        'mistral' => [
            'mistral-',
            'ministral-',
        ],
    ],

    'image_models' => [
        'gpt-image-1.5',
        'gemini-3-pro-image-preview',
        'grok-imagine-image',
        'dall-e-2',
        'dall-e-3',
    ],
];
