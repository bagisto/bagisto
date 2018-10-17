<?php

namespace Webkul\Core;

use Carbon\Carbon;
use Webkul\Core\Repositories\CurrencyRepository;
use Webkul\Core\Repositories\ExchangeRateRepository;
use Webkul\Core\Repositories\CountryRepository;
use Webkul\Core\Repositories\CountryStateRepository;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Core\Repositories\LocaleRepository;
use Illuminate\Support\Facades\Config;

class Core
{
    /**
     * ChannelRepository class
     *
     * @var mixed
     */
    protected $channelRepository;

    /**
     * CurrencyRepository class
     *
     * @var mixed
     */
    protected $currencyRepository;

    /**
     * ExchangeRateRepository class
     *
     * @var mixed
     */
    protected $exchangeRateRepository;

    /**
     * CountryRepository class
     *
     * @var mixed
     */
    protected $countryRepository;

    /**
     * CountryStateRepository class
     *
     * @var mixed
     */
    protected $countryStateRepository;

    /**
     * LocaleRepository class
     *
     * @var mixed
     */
    protected $localeRepository;

    /**
     * Create a new instance.
     *
     * @param  Webkul\Core\Repositories\ChannelRepository      $channelRepository
     * @param  Webkul\Core\Repositories\CurrencyRepository     $currencyRepository
     * @param  Webkul\Core\Repositories\ExchangeRateRepository $exchangeRateRepository
     * @param  Webkul\Core\Repositories\CountryRepository      $countryRepository
     * @param  Webkul\Core\Repositories\CountryStateRepository $countryStateRepository
     * @param  Webkul\Core\Repositories\LocaleRepository       $localeRepository
     * @return void
     */
    public function __construct(
        ChannelRepository $channelRepository,
        CurrencyRepository $currencyRepository,
        ExchangeRateRepository $exchangeRateRepository,
        CountryRepository $countryRepository,
        CountryStateRepository $countryStateRepository,
        LocaleRepository $localeRepository
    )
    {
        $this->channelRepository = $channelRepository;

        $this->currencyRepository = $currencyRepository;

        $this->exchangeRateRepository = $exchangeRateRepository;

        $this->countryRepository = $countryRepository;

        $this->countryStateRepository = $countryStateRepository;

        $this->localeRepository = $localeRepository;
    }

    /**
    * Returns all channels
    *
    *  @return Collection
    */
    public function getAllChannels() {
        static $channels;

        if($channels)
            return $channels;

        return $channels = $this->channelRepository->all();
    }

    /**
    * Returns currenct channel models
    *
    *  @return mixed
    */
    public function getCurrentChannel() {
        static $channel;

        if($channel)
            return $channel;

        return $channel = $this->channelRepository->first();
    }

    /**
    * Returns currenct channel code
    *
    *  @return string
    */
    public function getCurrentChannelCode() {
        static $channelCode;

        if($channelCode)
            return $channelCode;

        return ($channel = $this->getCurrentChannel()) ? $channelCode = $channel->code : '';
    }

    /**
    * Returns default channel models
    *
    *  @return mixed
    */
    public function getDefaultChannel() {
        static $channel;

        if($channel)
            return $channel;

        return $channel = $this->channelRepository->first();
    }

    /**
    * Returns default channel code
    *
    *  @return string
    */
    public function getDefaultChannelCode() {
        static $channelCode;

        if($channelCode)
            return $channelCode;

        return ($channel = $this->getDefaultChannel()) ? $channelCode = $channel->code : '';
    }

    /**
    * Returns all locales
    *
    *  @return Collection
    */
    public function getAllLocales() {
        static $locales;

        if($locales)
            return $locales;

        return $locales = $this->localeRepository->all();
    }

    /**
    * Returns all currencies
    *
    *  @return Collection
    */
    public function getAllCurrencies()
    {
        static $currencies;

        if($currencies)
            return $currencies;

        return $currencies = $this->currencyRepository->all();
    }

    /**
    * Returns base channel's currency model
    *
    *  @return mixed
    */
    public function getBaseCurrency()
    {
        static $currency;

        if($currency)
            return $currency;

        $baseCurrency = $this->currencyRepository->findOneByField('code', config('app.currency'));

        if(!$baseCurrency)
            $baseCurrency = $this->currencyRepository->first();

        return $currency = $baseCurrency;
    }

    /**
    * Returns base channel's currency code
    *
    *  @return string
    */
    public function getBaseCurrencyCode()
    {
        static $currencyCode;

        if($currencyCode)
            return $currencyCode;

        return ($currency = $this->getBaseCurrency()) ? $currencyCode = $currency->code : '';
    }

    /**
    * Returns base channel's currency symbol
    *
    *  @return string
    */
    public function getBaseCurrencySymbol()
    {
        static $currencySymbol;

        if($currencySymbol)
            return $currencySymbol;

        return $currencySymbol = $this->getBaseCurrency()->symbol ?? $this->getBaseCurrencyCode();
    }

    /**
    * Returns base channel's currency model
    *
    *  @return mixed
    */
    public function getChannelBaseCurrency()
    {
        static $currency;

        if($currency)
            return $currency;

        $currenctChannel = $this->getCurrentChannel();

        return $currency = $currenctChannel->base_currency;
    }

    /**
    * Returns base channel's currency code
    *
    *  @return string
    */
    public function getChannelBaseCurrencyCode()
    {
        static $currencyCode;

        if($currencyCode)
            return $currencyCode;

        return ($currency = $this->getChannelBaseCurrency()) ? $currencyCode = $currency->code : '';
    }

    /**
    * Returns base channel's currency symbol
    *
    *  @return string
    */
    public function getChannelBaseCurrencySymbol()
    {
        static $currencySymbol;

        if($currencySymbol)
            return $currencySymbol;

        return $currencySymbol = $this->getChannelBaseCurrency()->symbol;
    }

    /**
    * Returns current channel's currency model
    *
    *  @return mixed
    */
    public function getCurrentCurrency()
    {
        static $currency;

        if($currency)
            return $currency;

        return $currency = $this->currencyRepository->first();
    }

    /**
    * Returns current channel's currency code
    *
    *  @return string
    */
    public function getCurrentCurrencyCode()
    {
        static $currencyCode;

        if($currencyCode)
            return $currencyCode;

        return ($currency = $this->getCurrentCurrency()) ? $currencyCode = $currency->code : '';
    }

    /**
    * Returns current channel's currency symbol
    *
    *  @return string
    */
    public function getCurrentCurrencySymbol()
    {
        static $currencySymbol;

        if($currencySymbol)
            return $currencySymbol;

        return $currencySymbol = $this->getCurrentCurrency()->symbol;
    }

    /**
    * Converts price
    *
    * @param float  $price
    * @param string $targetCurrencyCode
    * @return string
    */
    public function convertPrice($amount, $targetCurrencyCode = null)
    {
        $targetCurrency = !$targetCurrencyCode
                        ? $this->getCurrentCurrency()
                        : $this->currencyRepository->findByField('code', $targetCurrencyCode);

        if(!$targetCurrency)
            return $amount;

        $exchangeRate = $this->exchangeRateRepository->findOneWhere([
            'target_currency' => $targetCurrency->id,
        ]);

        if (null === $exchangeRate)
            return $amount;

        return (float) round($amount * $exchangeRate->rate);
    }

    /**
    * Format and convert price with currency symbol
    *
    * @param float $price
    *  @return string
    */
    public function currency($amount = 0)
    {
        if(is_null($amount))
            $amount = 0;

        $currencyCode = $this->getCurrentCurrency()->code;

        return currency($this->convertPrice($amount), $currencyCode);
    }

    /**
    * Format and convert price with currency symbol
    *
    * @param float $price
    *  @return string
    */
    public function formatPrice($price, $currencyCode)
    {
        if(is_null($price))
            $price = 0;

        return currency($price, $currencyCode);
    }

    /**
    * Format price with base currency symbol
    *
    * @param float $price
    *  @return string
    */
    public function formatBasePrice($price)
    {
        if(is_null($price))
            $price = 0;
            
        return currency($price, $this->getBaseCurrencyCode());
    }

    /**
     * Checks if current date of the given channel (in the channel timezone) is within the range
     *
     * @param int|string|Channel $channel
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @return bool
     */
    public function isChannelDateInInterval($dateFrom = null, $dateTo = null)
    {
        $channel = $this->getCurrentChannel();

        $channelTimeStamp = $this->channelTimeStamp($channel);

        $fromTimeStamp  = strtotime($dateFrom);

        $toTimeStamp    = strtotime($dateTo);

        if ($dateTo) {
            $toTimeStamp += 86400;
        }

        $result = false;

        if (!$this->is_empty_date($dateFrom) && $channelTimeStamp < $fromTimeStamp) {
        } elseif (!$this->is_empty_date($dateTo) && $channelTimeStamp > $toTimeStamp) {
        } else {
            $result = true;
        }

        return $result;
    }

    /**
     * Get channel timestamp
     * Timstamp will be builded with channel timezone settings
     *
     * @param   mixed $channel
     * @return  int
     */
    public function channelTimeStamp($channel)
    {
        $timezone = $channel->timezone;

        $currentTimezone = @date_default_timezone_get();

        @date_default_timezone_set($timezone);

        $date = date('Y-m-d H:i:s');

        @date_default_timezone_set($currentTimezone);

        return strtotime($date);
    }

    /**
    * Check whether sql date is empty
    *
    * @param string $date
    * @return boolean
    */
    function is_empty_date($date)
    {
        return preg_replace('#[ 0:-]#', '', $date) === '';
    }

    /**
     * Format date using current channel.
     *
     * @param   date|null $date
     * @param   string    $format
     * @return  string
     */
    public function formatDate($date = null, $format = 'd-m-Y H:i:s')
    {
        $channel = $this->getCurrentChannel();

        if (is_null($date)) {
            $date = Carbon::now();
        }

        $date->setTimezone($channel->timezone);

        return $date->format($format);
    }

    /**
     * Retrieve information from payment configuration
     *
     * @param string $field
     * @param int|string|null $channelId
     *
     * @return mixed
     */
    public function getConfigData($field, $channelId = null)
    {
        if (null === $channelId) {
            $channelId = $this->getCurrentChannel()->id;
        }

        return Config::get($field);
    }

    /**
     * Retrieve all countries
     *
     * @return Collection
     */
    public function countries()
    {
        return $this->countryRepository->all();
    }

    /**
     * Retrieve all country states
     *
     * @return Collection
     */
    public function states($countryCode)
    {
        return $this->countryStateRepository->findByField('country_code', $countryCode);
    }

    /**
     * Retrieve all grouped states by country code
     *
     * @return Collection
     */
    public function groupedStatesByCountries()
    {
        $collection = [];

        foreach ($this->countries() as $country) {
            $collection[$country->code] = $this->states($country->code)->toArray();
        }

        return $collection;
    }
}