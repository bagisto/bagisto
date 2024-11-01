<?php

declare(strict_types=1);

namespace Frosko\DSK\Services\Actions;

use Frosko\DSK\Services\DskBankConfig;
use Frosko\DSK\Enums\ApiDefaults;
use Frosko\DSK\Exceptions\DskBankException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

abstract class AbstractDskAction
{
    protected PendingRequest $http;

    public function __construct(
        protected readonly DskBankConfig $config
    ) {
        $this->http = Http::baseUrl($this->config->getBaseUrl())
            ->withUserAgent(ApiDefaults::USER_AGENT)
            ->timeout(ApiDefaults::API_TIMEOUT_SECS)
            ->retry(
                ApiDefaults::API_RETRY_TIMES,
                ApiDefaults::API_RETRY_SLEEP_MS
            );
    }

    protected function handleResponse(Response $response): array
    {
        if ($response->failed()) {
            throw new DskBankException(
                $response->json('errorMessage') ?? 'Unknown error',
                $response->json('errorCode') ?? 0
            );
        }

        return $response->json();
    }
}
