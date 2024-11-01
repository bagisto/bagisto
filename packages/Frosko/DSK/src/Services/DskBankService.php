<?php

declare(strict_types=1);

namespace Frosko\DSK\Services;

use Frosko\DSK\Enums\Currency;
use Frosko\DSK\Enums\Locale;
use Frosko\DSK\Enums\PageView;
use Frosko\DSK\Services\Actions\CreateRefund;
use Frosko\DSK\Services\Actions\GetTransactionInfo;
use Frosko\DSK\Services\Actions\ProcessWebhook;
use Frosko\DSK\Services\Actions\RegisterPayment;

readonly class DskBankService
{
    public function __construct(
        private DskBankConfig $config
    ) {}

    public function registerPayment(
        int $amount,
        Currency $currency,
        string $orderNumber,
        string $description,
        ?Locale $locale = null,
        ?PageView $pageView = null,
        ?array $extraParams = null
    ): array {
        return (new RegisterPayment($this->config))
            ->execute($amount, $currency, $orderNumber, $description, $locale, $pageView, $extraParams);
    }

    public function processWebhook(string $orderId, string $orderNumber): array
    {
        return (new ProcessWebhook($this->config))
            ->execute($orderId, $orderNumber);
    }

    public function createRefund(string $orderId, int $amount): array
    {
        return (new CreateRefund($this->config))
            ->execute($orderId, $amount);
    }

    public function getTransactionInfo(string $orderId): array
    {
        return (new GetTransactionInfo($this->config))
            ->execute($orderId);
    }
}
