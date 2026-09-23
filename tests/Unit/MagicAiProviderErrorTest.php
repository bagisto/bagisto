<?php

use GuzzleHttp\Psr7\Response as PsrResponse;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Webkul\MagicAI\ProviderError;

/**
 * The exception the SDK throws when a provider refuses a request with the given body.
 */
function providerRequestException(array|string $body, int $status = 400): RequestException
{
    return new RequestException(
        new Response(new PsrResponse($status, [], is_array($body) ? json_encode($body) : $body)),
    );
}

// ============================================================================
// Provider Messages
// ============================================================================

it('should read the message a provider returned', function (array|string $body, string $expected) {
    expect(ProviderError::message(providerRequestException($body)))->toBe($expected);
})->with([
    'openai' => [['error' => ['message' => 'Your request was rejected by the safety system.', 'type' => 'image_generation_user_error']], 'Your request was rejected by the safety system.'],
    'anthropic' => [['type' => 'error', 'error' => ['type' => 'invalid_request_error', 'message' => 'max_tokens is too large.']], 'max_tokens is too large.'],
    'gemini' => [['error' => ['code' => 400, 'message' => 'API key not valid. Please pass a valid API key.', 'status' => 'INVALID_ARGUMENT']], 'API key not valid. Please pass a valid API key.'],
    'ollama' => [['error' => 'model "llama3.2:3b" not found, try pulling it first'], 'model "llama3.2:3b" not found, try pulling it first'],
    'a bare message' => [['message' => 'Quota exceeded.'], 'Quota exceeded.'],
    'whitespace around it' => [['error' => ['message' => "  Trimmed.\n"]], 'Trimmed.'],
]);

it('should fall back to the exception message when the body carries none', function (array|string $body) {
    expect(ProviderError::message(providerRequestException($body)))
        ->toStartWith('HTTP request returned status code 400');
})->with([
    'an empty body' => [''],
    'html' => ['<html><body>Bad Gateway</body></html>'],
    'an unexpected shape' => [['detail' => ['code' => 12]]],
    'an empty message' => [['error' => ['message' => '   ']]],
]);

it('should use the exception message when the failure never reached the provider', function () {
    expect(ProviderError::message(new RuntimeException('AI provider [openai] is not supported by the Laravel AI SDK.')))
        ->toBe('AI provider [openai] is not supported by the Laravel AI SDK.');
});
