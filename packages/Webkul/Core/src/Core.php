<?php

namespace Webkul\Core;

use Webkul\Core\Models\Channel as ChannelModel;
use Webkul\Core\Models\Locale as LocaleModel;
use Webkul\Core\Models\Currency as CurrencyModel;

class Core
{
    /**
    * Returns all channels
    *
    *  @return Collection
    */
    public function getAllChannels() {
        return ChannelModel::all();
    }

    /**
    * Returns currenct channel models
    *
    *  @return mixed
    */
    public function getCurrentChannel() {
        return ChannelModel::first();
    }

    /**
    * Returns currenct channel code
    *
    *  @return string
    */
    public function getCurrentChannelCode() {
        return ($channel = $this->getCurrentChannel()) ? $channel->code : '';
    }

    /**
    * Returns all locales
    *
    *  @return Collection
    */
    public function getAllLocales() {
        return LocaleModel::all();
    }

    /**
    * Returns all currencies
    *
    *  @return Collection
    */
    public function getAllCurrencies()
    {
        return CurrencyModel::all();
    }

    /**
    * Returns current channel's currency model
    *
    *  @return mixed
    */
    public function getCurrentCurrency()
    {
        $currency = CurrencyModel::first();

        return $currency;
    }

    /**
    * Returns current channel's currency code
    *
    *  @return string
    */
    public function getCurrentCurrencyCode()
    {
        return ($currency = $this->getCurrentCurrency()) ? $currency->code : '';
    }

    /**
    * Returns current channel's currency symbol
    *
    *  @return string
    */
    public function getCurrentCurrencySymbol()
    {
        return $this->getCurrentCurrency()->symbol;
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

        $currencyCode = $channel->base_currency->code;
        
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
}