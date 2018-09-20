<?php

namespace Webkul\Core;

use Carbon\Carbon;
use Webkul\Core\Models\Channel as ChannelModel;
use Webkul\Core\Models\Locale as LocaleModel;
use Webkul\Core\Models\Currency as CurrencyModel;
use Webkul\Core\Models\TaxCategory as TaxCategory;
use Webkul\Core\Models\TaxRate as TaxRate;

class Core
{
    /**
    * Returns all channels
    *
    *  @return Collection
    */
    public function getAllChannels() {
        static $channels;

        if($channels)
            return $channels;

        return $channels = ChannelModel::all();
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

        return $channel = ChannelModel::first();
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
    * Returns all locales
    *
    *  @return Collection
    */
    public function getAllLocales() {
        static $locales;

        if($locales)
            return $locales;

        return $locales = LocaleModel::all();
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

        return $currencies = CurrencyModel::all();
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

        return $currency = CurrencyModel::first();
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
    * Format and convert price with currency symbol
    *
    * @param float $price
    *  @return string
    */
    public function currency($price)
    {
        if(!$price)
            $price = 0;

        $channel = $this->getCurrentChannel();

        // $currencyCode = $channel->base_currency->code;
        $currencyCode = $channel->base_currency;

        return currency($price, $currencyCode);
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

    // $timezonelist = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);

    /**
     * Find all the tax
     * rates associated
     * with a tax category.
     *
     * @return Array
     */

    public function withRates($id) {
        return TaxCategory::findOrFail($id)->tax_rates;
    }

    /**
     * To fetch all
     * tax rates.
     *
     * @return Collection
     */
    public function getAllTaxRates() {
        return TaxRate::all();
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
}