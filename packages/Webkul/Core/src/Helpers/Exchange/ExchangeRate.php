<?php

namespace Webkul\Core\Helpers\Exchange;

use Webkul\Core\Repositories\CurrencyRepository;
use Webkul\Core\Repositories\ExchangeRateRepository;

abstract class ExchangeRate
{
    /**
     * Create a new exchange rate instance.
     */
    public function __construct(
        protected CurrencyRepository $currencyRepository,
        protected ExchangeRateRepository $exchangeRateRepository
    ) {}

    /**
     * Fetch rates from the API and update the `currency_exchange_rates` table.
     */
    abstract public function updateRates(): void;

    /**
     * Resolve the configured exchange rate service instance.
     */
    public static function resolve(): static
    {
        $default = core()->getConfigData('general.exchange_rates.settings.default_service')
            ?: config('services.exchange_api.default');

        return app(config("services.exchange_api.{$default}.class"));
    }

    /**
     * Get the configured API key from core configuration with fallback to Laravel config.
     */
    protected function getApiKey(string $coreConfigKey, string $laravelConfigKey): ?string
    {
        return core()->getConfigData($coreConfigKey)
            ?: config($laravelConfigKey);
    }

    /**
     * Update or create the exchange rate for a given currency.
     */
    protected function updateOrCreateRate(mixed $currency, float $rate): void
    {
        if ($exchangeRate = $currency->exchange_rate) {
            $this->exchangeRateRepository->update([
                'rate' => $rate,
            ], $exchangeRate->id);
        } else {
            $this->exchangeRateRepository->create([
                'rate' => $rate,
                'target_currency' => $currency->id,
            ]);
        }
    }
}
