<?php

namespace Webkul\Core\Helpers\Exchange;

use Illuminate\Support\Facades\Http;
use RuntimeException;
use Webkul\Core\Repositories\CurrencyRepository;
use Webkul\Core\Repositories\ExchangeRateRepository;

class FixerExchange extends ExchangeRate
{
    /**
     * API endpoint.
     */
    protected string $apiEndPoint = 'http://data.fixer.io/api';

    /**
     * API key.
     */
    protected ?string $apiKey;

    /**
     * Create a new instance.
     */
    public function __construct(
        protected CurrencyRepository $currencyRepository,
        protected ExchangeRateRepository $exchangeRateRepository
    ) {
        parent::__construct($currencyRepository, $exchangeRateRepository);

        $this->apiKey = $this->getApiKey(
            'general.exchange_rates.fixer.api_key',
            'services.exchange_api.fixer.key'
        );
    }

    /**
     * Fetch rates and update the `currency_exchange_rates` table.
     */
    public function updateRates(): void
    {
        $baseCurrency = config('app.currency');

        foreach ($this->currencyRepository->all() as $currency) {
            if ($currency->code === $baseCurrency) {
                continue;
            }

            $response = Http::get("{$this->apiEndPoint}/".date('Y-m-d'), [
                'access_key' => $this->apiKey,
                'base' => $baseCurrency,
                'symbols' => $currency->code,
            ]);

            $result = $response->json();

            if (isset($result['success']) && ! $result['success']) {
                throw new RuntimeException($result['error']['info'] ?? $result['error']['type']);
            }

            $this->updateOrCreateRate($currency, $result['rates'][$currency->code]);
        }
    }
}
