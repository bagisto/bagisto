<?php

namespace Webkul\Core\Helpers\Exchange;

use Webkul\Core\Repositories\CurrencyRepository;
use Webkul\Core\Repositories\ExchangeRateRepository;

class ExchangeRates extends ExchangeRate
{
    /**
     * API key.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * API endpoint.
     *
     * @var string
     */
    protected $apiEndPoint;

    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(
        protected CurrencyRepository $currencyRepository,
        protected ExchangeRateRepository $exchangeRateRepository
    ) {
        $this->apiEndPoint = config('services.exchange_api.exchange_rates.url');

        $this->apiKey = config('services.exchange_api.exchange_rates.key');
    }

    /**
     * Fetch rates and updates in `currency_exchange_rates` table.
     *
     * @return \Exception|void
     */
    public function updateRates()
    {
        $client = new \GuzzleHttp\Client;

        foreach ($this->currencyRepository->all() as $currency) {
            if ($currency->code == config('app.currency')) {
                continue;
            }

            $result = $client->request(
                'GET',
                $this->apiEndPoint, [
                    'headers' => [
                        'Content-Type' => 'text/plain',
                        'apikey'       => $this->apiKey,
                    ],
                    'query' => [
                        'to'     => $currency->code,
                        'from'   => config('app.currency'),
                        'amount' => 1,
                    ],
                ]
            );

            $result = json_decode($result->getBody()->getContents(), true);

            if (
                isset($result['success'])
                && ! $result['success']
            ) {
                throw new \Exception($result['error']['info'] ?? $result['error']['type'], 1);
            }

            if ($exchangeRate = $currency->exchange_rate) {
                $this->exchangeRateRepository->update([
                    'rate' => $result['result'],
                ], $exchangeRate->id);
            } else {
                $this->exchangeRateRepository->create([
                    'rate'            => $result['result'],
                    'target_currency' => $currency->id,
                ]);
            }
        }
    }
}
