<?php

declare(strict_types=1);

namespace Frosko\DSK\Services\Actions;

use Frosko\DSK\Enums\ApiDefaults;
use Frosko\DSK\Enums\Currency;
use Frosko\DSK\Enums\Locale;
use Frosko\DSK\Enums\PageView;

class RegisterPayment extends AbstractDskAction
{
    public function execute(
        int $amount,
        Currency $currency,
        string $orderNumber,
        string $description,
        ?Locale $locale = null,
        ?PageView $pageView = null,
        ?array $extraParams = null
    ): array {
        $response = $this->http->asForm()->post('register.do', [
            ...$this->config->getCredentials(),
            'orderNumber'        => $orderNumber,
            'amount'             => $amount,
            'currency'           => $currency->iso975(),
            'returnUrl'          => $this->config->getReturnUrl(),
            'failUrl'            => $this->config->getFailUrl(),
            'dynamicCallbackUrl' => $this->config->getDynamicCallbackUrl(),
            'description'        => $description,
            'language'           => ($locale ?? Locale::default())->value,
//            'pageView'           => ($pageView ?? PageView::default())->value,
            'sessionTimeoutSecs' => ApiDefaults::SESSION_TIMEOUT_SECS,
            'jsonParams'         => json_encode([
                'force_terminal_id' => '',
                'udf_1'             => $orderNumber,
                ...$extraParams ?? [],
            ], JSON_THROW_ON_ERROR),
        ]);

        return $this->handleResponse($response);
    }
}
