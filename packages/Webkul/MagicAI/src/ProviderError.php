<?php

namespace Webkul\MagicAI;

use Illuminate\Http\Client\RequestException;
use Throwable;

class ProviderError
{
    /**
     * The keys a provider returns its own message under, most specific first.
     */
    private const MESSAGE_KEYS = ['error.message', 'message', 'error'];

    /**
     * The message the provider gave for a failed request, falling back to the exception's own.
     */
    public static function message(Throwable $e): string
    {
        if (! $e instanceof RequestException) {
            return $e->getMessage();
        }

        foreach (self::MESSAGE_KEYS as $key) {
            $message = $e->response->json($key);

            if (is_string($message) && trim($message) !== '') {
                return trim($message);
            }
        }

        return $e->getMessage();
    }
}
