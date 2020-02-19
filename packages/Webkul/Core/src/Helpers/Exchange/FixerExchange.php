<?php

namespace Webkul\Core\Helpers\Exchange;

use Webkul\Core\Helpers\Exchange\ExchangeRate;
use Webkul\Core\Repositories\ExchangeRateRepository;

class FixerExchange  extends ExchangeRate
{
    /**
     * API key
     */
    protected $apiKey;

    /**
     * API endpoint
     */
    protected $apiEndPoint;

    /**
     * Holds ExchangeRateRepository instance
     */
    protected $exchangeRate;

    public function  __construct()
    {
        $this->apiKey = config('services.exchange-api')['fixer']['key'];

        $this->apiEndPoint = 'http://data.fixer.io/api/latest?access_key=' . $this->apiKey;
    }

    public function fetchRates()
    {
        $rates = array();

        $this->exchangeRate = app('Webkul\Core\Repositories\ExchangeRateRepository');

        // dummy api call
        $client = new \GuzzleHttp\Client();

        if (config('services.exchange-api')['fixer']['paid_account']) {
            $result = $client->request('GET', 'http://data.fixer.io/api/' . date('Y-m-d').'?access_key=' . $this->apiKey.'&base=' . core()->getBaseCurrency()->code . '&symbols=INR');
        } else {
            $result = $client->request('GET', 'http://data.fixer.io/api/' . date('Y-m-d') . '?access_key=' . $this->apiKey . '&symbols=USD');
        }

        $result = json_decode($result->getBody()->getContents());

        return $result;
    }

    public function updateRates()
    {
    }
}