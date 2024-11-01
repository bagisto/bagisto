<?php

declare(strict_types=1);

namespace Frosko\DSK\Services;

class DskBankConfig
{
    public function __construct(
        private readonly string $username,
        private readonly string $password,
        private readonly string $returnUrl,
        private readonly string $failUrl,
        private readonly string $dynamicCallbackUrl,
        private readonly bool $testMode = true,
    ) {}

    public function getBaseUrl(): string
    {
        return $this->testMode
            ? 'https://uat.dskbank.bg/payment/rest/'
            : 'https://dskbank.bg/payment/rest/';
    }

    public function getCredentials(): array
    {
        return [
            'token' => '2oh3osrkb24l61liaq5qn619gh',
//            'password' => $this->password,
        ];
    }

    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    public function getFailUrl(): string
    {
        return $this->failUrl;
    }

    public function getDynamicCallbackUrl(): string
    {
        return $this->dynamicCallbackUrl;
    }
}
