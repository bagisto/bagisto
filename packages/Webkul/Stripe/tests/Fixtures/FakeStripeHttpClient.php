<?php

namespace Webkul\Stripe\Tests\Fixtures;

use Stripe\HttpClient\ClientInterface;
use Stripe\Util\CaseInsensitiveArray;

class FakeStripeHttpClient implements ClientInterface
{
    /**
     * The requests sent to Stripe, in the order they were sent.
     */
    public array $requests = [];

    /**
     * Create a client answering each request from the responses keyed by method and path, such as `GET /v1/checkout/sessions`.
     */
    public function __construct(protected array $responses = []) {}

    /**
     * Record the request and answer it from the canned responses, or with Stripe's not found error.
     */
    public function request($method, $absUrl, $headers, $params, $hasFile, $apiMode = 'v1', $maxNetworkRetries = null)
    {
        $path = parse_url($absUrl, PHP_URL_PATH);

        $this->requests[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'params' => $params,
        ];

        [$status, $body] = $this->responses[strtoupper($method).' '.$path] ?? [404, [
            'error' => [
                'type' => 'invalid_request_error',
                'message' => 'No such resource.',
            ],
        ]];

        return [json_encode($body), $status, new CaseInsensitiveArray];
    }

    /**
     * The requests sent to the given method and path.
     */
    public function requestsTo(string $method, string $path): array
    {
        return array_values(array_filter(
            $this->requests,
            fn ($request) => $request['method'] === $method && $request['path'] === $path
        ));
    }
}
