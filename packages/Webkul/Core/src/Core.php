<?php

namespace Webkul\Core;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Concerns\CurrencyFormatter;
use Webkul\Core\Models\Channel;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Core\Repositories\CoreConfigRepository;
use Webkul\Core\Repositories\CountryRepository;
use Webkul\Core\Repositories\CountryStateRepository;
use Webkul\Core\Repositories\CurrencyRepository;
use Webkul\Core\Repositories\ExchangeRateRepository;
use Webkul\Core\Repositories\LocaleRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Tax\Repositories\TaxCategoryRepository;

class Core
{
    use CurrencyFormatter;

    /**
     * The Bagisto version.
     *
     * @var string
     */
    const BAGISTO_VERSION = '2.1.x-dev';

    /**
     * Current Channel.
     *
     * @var \Webkul\Core\Models\Channel
     */
    protected $currentChannel;

    /**
     * Default Channel.
     *
     * @var \Webkul\Core\Models\Channel
     */
    protected $defaultChannel;

    /**
     * Currency.
     *
     * @var \Webkul\Core\Models\Currency
     */
    protected $currentCurrency;

    /**
     * Base Currency.
     *
     * @var \Webkul\Core\Models\Currency
     */
    protected $baseCurrency;

    /**
     * Current Locale.
     *
     * @var \Webkul\Core\Models\Locale
     */
    protected $currentLocale;

    /**
     * Guest Customer Group
     *
     * @var \Webkul\Customer\Models\CustomerGroup
     */
    protected $guestCustomerGroup;

    /**
     * Exchange rates
     *
     * @var array
     */
    protected $exchangeRates = [];

    /**
     * Exchange rates
     *
     * @var array
     */
    protected $taxCategoriesById = [];

    /**
     * Stores singleton instances
     *
     * @var array
     */
    protected $singletonInstances = [];

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(
        protected ChannelRepository $channelRepository,
        protected CurrencyRepository $currencyRepository,
        protected ExchangeRateRepository $exchangeRateRepository,
        protected CountryRepository $countryRepository,
        protected CountryStateRepository $countryStateRepository,
        protected LocaleRepository $localeRepository,
        protected CoreConfigRepository $coreConfigRepository,
        protected CustomerGroupRepository $customerGroupRepository,
        protected TaxCategoryRepository $taxCategoryRepository
    ) {
    }

    /**
     * Get the version number of the Bagisto.
     *
     * @return string
     */
    public function version()
    {
        return static::BAGISTO_VERSION;
    }

    /**
     * Returns all channels.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllChannels()
    {
        return $this->channelRepository->all();
    }

    /**
     * Returns current channel models.
     *
     * @return \Webkul\Core\Contracts\Channel
     */
    public function getCurrentChannel(?string $hostname = null)
    {
        if (! $hostname) {
            $hostname = request()->getHttpHost();
        }

        if ($this->currentChannel) {
            return $this->currentChannel;
        }

        $this->currentChannel = $this->channelRepository->findWhereIn('hostname', [
            $hostname,
            'http://'.$hostname,
            'https://'.$hostname,
        ])->first();

        if (! $this->currentChannel) {
            $this->currentChannel = $this->channelRepository->first();
        }

        return $this->currentChannel;
    }

    /**
     * Set the current channel.
     */
    public function setCurrentChannel(Channel $channel): void
    {
        $this->currentChannel = $channel;
    }

    /**
     * Returns current channel code.
     *
     * @return \Webkul\Core\Contracts\Channel
     */
    public function getCurrentChannelCode(): string
    {
        return $this->getCurrentChannel()?->code;
    }

    /**
     * Returns default channel models.
     *
     * @return \Webkul\Core\Contracts\Channel
     */
    public function getDefaultChannel(): ?Channel
    {
        if ($this->defaultChannel) {
            return $this->defaultChannel;
        }

        $this->defaultChannel = $this->channelRepository->findOneByField('code', config('app.channel'));

        if ($this->defaultChannel) {
            return $this->defaultChannel;
        }

        return $this->defaultChannel = $this->channelRepository->first();
    }

    /**
     * Set the default channel.
     */
    public function setDefaultChannel(Channel $channel): void
    {
        $this->defaultChannel = $channel;
    }

    /**
     * Returns the default channel code configured in `config/app.php`.
     */
    public function getDefaultChannelCode(): string
    {
        return $this->getDefaultChannel()?->code;
    }

    /**
     * Returns default locale code from default channel.
     */
    public function getDefaultLocaleCodeFromDefaultChannel(): string
    {
        return $this->getDefaultChannel()->default_locale->code;
    }

    /**
     * Get channel code from request.
     *
     * @return \Webkul\Core\Contracts\Channel
     */
    public function getRequestedChannel()
    {
        $code = request()->query('channel');

        if ($code) {
            return $this->channelRepository->findOneByField('code', $code);
        }

        return $this->getCurrentChannel();
    }

    /**
     * Get channel code from request.
     *
     * @param  bool  $fallback  optional
     * @return string
     */
    public function getRequestedChannelCode($fallback = true)
    {
        $channelCode = request()->get('channel');

        if (! $fallback) {
            return $channelCode;
        }

        return $channelCode ?: ($this->getCurrentChannelCode() ?: $this->getDefaultChannelCode());
    }

    /**
     * Returns the channel name.
     */
    public function getChannelName($channel): string
    {
        return $channel->name ?? $channel->translate(app()->getLocale())->name ?? $channel->translate(config('app.fallback_locale'))->name;
    }

    /**
     * Return all locales.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllLocales()
    {
        return $this->localeRepository->all()->sortBy('name');
    }

    /**
     * Returns current locale.
     *
     * @return \Webkul\Core\Contracts\Locale
     */
    public function getCurrentLocale()
    {
        if ($this->currentLocale) {
            return $this->currentLocale;
        }

        $this->currentLocale = $this->localeRepository->findOneByField('code', app()->getLocale());

        if (! $this->currentLocale) {
            $this->currentLocale = $this->localeRepository->findOneByField('code', config('app.fallback_locale'));
        }

        return $this->currentLocale;
    }

    /**
     * Get locale from request.
     *
     * @return string
     */
    public function getRequestedLocale()
    {
        $code = request()->query('locale');

        if ($code) {
            return $this->localeRepository->findOneByField('code', $code);
        }

        return $this->getCurrentLocale();
    }

    /**
     * Get locale code from request. Here if you want to use admin locale,
     * you can pass it as an argument.
     *
     * @param  string  $localeKey  optional
     * @param  bool  $fallback  optional
     * @return string
     */
    public function getRequestedLocaleCode($localeKey = 'locale', $fallback = true)
    {
        $localeCode = request()->get($localeKey);

        if (! $fallback) {
            return $localeCode;
        }

        return $localeCode ?: app()->getLocale();
    }

    /**
     * Check requested locale code in requested channel. If not found,
     * then set channel default locale code.
     *
     * @return string
     */
    public function getRequestedLocaleCodeInRequestedChannel()
    {
        $requestedLocaleCode = $this->getRequestedLocaleCode();

        $requestedChannel = $this->getRequestedChannel();

        if ($requestedChannel->locales->contains('code', $requestedLocaleCode)) {
            return $requestedLocaleCode;
        }

        return $requestedChannel->default_locale->code;
    }

    /**
     * Returns all currencies.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllCurrencies()
    {
        return $this->currencyRepository->all();
    }

    /**
     * Returns base channel's currency model.
     *
     * @return \Webkul\Core\Contracts\Currency
     */
    public function getBaseCurrency()
    {
        if ($this->baseCurrency) {
            return $this->baseCurrency;
        }

        $this->baseCurrency = $this->currencyRepository->findOneByField('code', config('app.currency'));

        if (! $this->baseCurrency) {
            $this->baseCurrency = $this->currencyRepository->first();
        }

        return $this->baseCurrency;
    }

    /**
     * Returns base channel's currency code.
     *
     * @return string
     */
    public function getBaseCurrencyCode()
    {
        return $this->getBaseCurrency()?->code;
    }

    /**
     * Returns base channel's currency model.
     *
     * @return \Webkul\Core\Contracts\Currency
     */
    public function getChannelBaseCurrency()
    {
        return $this->getCurrentChannel()->base_currency;
    }

    /**
     * Returns base channel's currency code.
     *
     * @return string
     */
    public function getChannelBaseCurrencyCode()
    {
        return $this->getChannelBaseCurrency()?->code;
    }

    /**
     * Set currency.
     *
     * @param  string  $currencyCode
     * @return void
     */
    public function setCurrentCurrency($currencyCode)
    {
        $this->currentCurrency = $this->currencyRepository->findOneByField('code', $currencyCode);

        if ($this->currentCurrency) {
            return;
        }

        $this->currentCurrency = $this->getChannelBaseCurrency();
    }

    /**
     * Returns current channel's currency model.
     *
     * Will fallback to base currency if not set.
     *
     * @return \Webkul\Core\Contracts\Currency
     */
    public function getCurrentCurrency()
    {
        if ($this->currentCurrency) {
            return $this->currentCurrency;
        }

        return $this->currentCurrency = $this->getChannelBaseCurrency();
    }

    /**
     * Returns current channel's currency code.
     *
     * @return string
     */
    public function getCurrentCurrencyCode()
    {
        return $this->getCurrentCurrency()?->code;
    }

    /**
     * Returns exchange rates.
     *
     * @return object
     */
    public function getExchangeRate($targetCurrencyId)
    {
        if (array_key_exists($targetCurrencyId, $this->exchangeRates)) {
            return $this->exchangeRates[$targetCurrencyId];
        }

        return $this->exchangeRates[$targetCurrencyId] = $this->exchangeRateRepository->findOneWhere([
            'target_currency' => $targetCurrencyId,
        ]);
    }

    /**
     * Converts price.
     *
     * @param  float  $amount
     * @param  string  $targetCurrencyCode
     * @return string
     */
    public function convertPrice($amount, $targetCurrencyCode = null)
    {
        $targetCurrency = ! $targetCurrencyCode
            ? $this->getCurrentCurrency()
            : $this->currencyRepository->findOneByField('code', $targetCurrencyCode);

        if (! $targetCurrency) {
            return $amount;
        }

        $exchangeRate = $this->getExchangeRate($targetCurrency->id);

        if (! $exchangeRate) {
            return $amount;
        }

        return (float) $amount * $exchangeRate->rate;
    }

    /**
     * Converts to base price.
     *
     * @param  float  $amount
     * @param  string  $targetCurrencyCode
     * @return string
     */
    public function convertToBasePrice($amount, $targetCurrencyCode = null)
    {
        $targetCurrency = ! $targetCurrencyCode
            ? $this->getCurrentCurrency()
            : $this->currencyRepository->findOneByField('code', $targetCurrencyCode);

        if (! $targetCurrency) {
            return $amount;
        }

        $exchangeRate = $this->exchangeRateRepository->findOneWhere([
            'target_currency' => $targetCurrency->id,
        ]);

        if (
            $exchangeRate === null
            || ! $exchangeRate->rate
        ) {
            return $amount;
        }

        return (float) $amount / $exchangeRate->rate;
    }

    /**
     * Format and convert price with currency symbol.
     *
     * @param  float  $price
     * @return string
     */
    public function currency($amount = 0)
    {
        if (is_null($amount)) {
            $amount = 0;
        }

        return $this->formatPrice($this->convertPrice($amount));
    }

    /**
     * Format price.
     */
    public function formatPrice(?float $price, ?string $currencyCode = null): string
    {
        if (is_null($price)) {
            $price = 0;
        }

        $currency = $currencyCode
            ? $this->getAllCurrencies()->where('code', $currencyCode)->first()
            : $this->getCurrentCurrency();

        return $this->formatCurrency($price, $currency);
    }

    /**
     * Format price with base currency symbol.
     */
    public function formatBasePrice(?float $price): string
    {
        if (is_null($price)) {
            $price = 0;
        }

        $currency = $this->getBaseCurrency();

        return $this->formatCurrency($price, $currency);
    }

    /**
     * Checks if current date of the given channel (in the channel timezone) is within the range.
     *
     * @param  int|string|\Webkul\Core\Contracts\Channel  $channel
     * @param  string|null  $dateFrom
     * @param  string|null  $dateTo
     * @return bool
     */
    public function isChannelDateInInterval($dateFrom = null, $dateTo = null)
    {
        $channel = $this->getCurrentChannel();

        $channelTimeStamp = $this->channelTimeStamp($channel);

        $fromTimeStamp = strtotime($dateFrom);

        $toTimeStamp = strtotime($dateTo);

        if ($dateTo) {
            $toTimeStamp += 86400;
        }

        if (
            ! $this->is_empty_date($dateFrom)
            && $channelTimeStamp < $fromTimeStamp
        ) {
            $result = false;
        } elseif (
            ! $this->is_empty_date($dateTo)
            && $channelTimeStamp > $toTimeStamp
        ) {
            $result = false;
        } else {
            $result = true;
        }

        return $result;
    }

    /**
     * Get channel timestamp, timestamp will be builded with channel timezone settings.
     *
     * @param  \Webkul\Core\Contracts\Channel  $channel
     * @return int
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
     * Check whether sql date is empty.
     *
     * @param  string  $date
     * @return bool
     */
    public function is_empty_date($date)
    {
        return preg_replace('#[ 0:-]#', '', $date) === '';
    }

    /**
     * Format date using current channel.
     *
     * @param  \Illuminate\Support\Carbon|string|null  $date
     * @param  string  $format
     * @return string
     */
    public function formatDate($date = null, $format = 'd-m-Y H:i:s')
    {
        $channel = $this->getCurrentChannel();

        if (is_null($date)) {
            $date = Carbon::now();
        }

        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        $date->setTimezone($channel->timezone);

        return $date->format($format);
    }

    /**
     * Retrieve information from payment configuration.
     *
     * @param  string  $field
     * @param  int|string|null  $channelId
     * @param  string|null  $locale
     * @return mixed
     */
    public function getConfigData($field, $channel = null, $locale = null)
    {
        if (empty($channel)) {
            $channel = $this->getRequestedChannelCode();
        }

        if (empty($locale)) {
            $locale = $this->getRequestedLocaleCode();
        }

        $coreConfig = $this->getCoreConfig($field, $channel, $locale);

        if (! $coreConfig) {
            return $this->getDefaultConfig($field);
        }

        return $coreConfig->value;
    }

    /**
     * Retrieve all countries.
     *
     * @return \Illuminate\Support\Collection
     */
    public function countries()
    {
        return DB::table('countries')->get();
    }

    /**
     * Returns country name by code.
     *
     * @param  string  $code
     * @return string
     */
    public function country_name($code)
    {
        $country = $this->countryRepository->findOneByField('code', $code);

        return $country ? $country->name : '';
    }

    /**
     * Retrieve all country states.
     *
     * @param  string  $countryCode
     * @return \Illuminate\Support\Collection
     */
    public function states($countryCode)
    {
        return $this->countryStateRepository->findByField('country_code', $countryCode);
    }

    /**
     * Retrieve all grouped states by country code.
     *
     * @return \Illuminate\Support\Collection
     */
    public function groupedStatesByCountries()
    {
        $collection = [];

        foreach (DB::table('country_states')->get() as $state) {
            $collection[$state->country_code][] = $state;
        }

        return $collection;
    }

    /**
     * Retrieve all grouped states by country code.
     *
     * @return \Illuminate\Support\Collection
     */
    public function findStateByCountryCode($countryCode = null, $stateCode = null)
    {
        $collection = [];

        $collection = $this->countryStateRepository->findByField(['country_code' => $countryCode, 'code' => $stateCode]);

        if (count($collection)) {
            return $collection->first();
        } else {
            return false;
        }
    }

    /**
     * Return guest customer group.
     *
     * @return \Webkul\Customer\Contract\CustomerGroup
     */
    public function getGuestCustomerGroup()
    {
        if ($this->guestCustomerGroup) {
            return $this->guestCustomerGroup;
        }

        return $this->guestCustomerGroup = $this->customerGroupRepository->findOneByField('code', 'guest');
    }

    /**
     * Is country required.
     *
     * @return bool
     */
    public function isCountryRequired()
    {
        return (bool) $this->getConfigData('customer.address.requirements.country');
    }

    /**
     * Is state required.
     *
     * @return bool
     */
    public function isStateRequired()
    {
        return (bool) $this->getConfigData('customer.address.requirements.state');
    }

    /**
     * Is postcode required.
     *
     * @return bool
     */
    public function isPostCodeRequired()
    {
        return (bool) $this->getConfigData('customer.address.requirements.postcode');
    }

    /**
     * Week range.
     *
     * @param  string  $date
     * @param  int  $day
     * @return string
     */
    public function xWeekRange($date, $day)
    {
        $ts = strtotime($date);

        if (! $day) {
            $start = (date('D', $ts) == 'Sun') ? $ts : strtotime('last sunday', $ts);

            return date('Y-m-d', $start);
        } else {
            $end = (date('D', $ts) == 'Sat') ? $ts : strtotime('next saturday', $ts);

            return date('Y-m-d', $end);
        }
    }

    /**
     * Method to sort through the acl items and put them in order.
     *
     * @param  array  $items
     * @return array
     */
    public function sortItems($items)
    {
        foreach ($items as &$item) {
            if (count($item['children'])) {
                $item['children'] = $this->sortItems($item['children']);
            }
        }

        usort($items, function ($a, $b) {
            if ($a['sort'] == $b['sort']) {
                return 0;
            }

            return ($a['sort'] < $b['sort']) ? -1 : 1;
        });

        return $this->convertToAssociativeArray($items);
    }

    /**
     * Get config field.
     *
     * @param  string  $fieldName
     * @return array
     */
    public function getConfigField($fieldName)
    {
        foreach (config('core') as $coreData) {
            if (! isset($coreData['fields'])) {
                continue;
            }

            foreach ($coreData['fields'] as $field) {
                $name = $coreData['key'].'.'.$field['name'];

                if ($name == $fieldName) {
                    return $field;
                }
            }
        }
    }

    /**
     * Convert to associative array.
     *
     * @param  array  $items
     * @return array
     */
    public function convertToAssociativeArray($items)
    {
        foreach ($items as $key1 => $level1) {
            unset($items[$key1]);

            $items[$level1['key']] = $level1;

            if (! count($level1['children'])) {
                continue;
            }

            foreach ($level1['children'] as $key2 => $level2) {
                $temp2 = explode('.', $level2['key']);

                $finalKey2 = end($temp2);

                unset($items[$level1['key']]['children'][$key2]);

                $items[$level1['key']]['children'][$finalKey2] = $level2;

                if (! count($level2['children'])) {
                    continue;
                }

                foreach ($level2['children'] as $key3 => $level3) {
                    $temp3 = explode('.', $level3['key']);

                    $finalKey3 = end($temp3);

                    unset($items[$level1['key']]['children'][$finalKey2]['children'][$key3]);

                    $items[$level1['key']]['children'][$finalKey2]['children'][$finalKey3] = $level3;
                }
            }
        }

        return $items;
    }

    /**
     * Array set.
     *
     * @param  array  $items
     * @param  string  $key
     * @param  string|int|float  $value
     * @return array
     */
    public function array_set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);
        $count = count($keys);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (
                ! isset($array[$key])
                || ! is_array($array[$key])
            ) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $finalKey = array_shift($keys);

        if (isset($array[$finalKey])) {
            $array[$finalKey] = $this->arrayMerge($array[$finalKey], $value);
        } else {
            $array[$finalKey] = $value;
        }

        return $array;
    }

    /**
     * Convert empty strings to null.
     *
     * @param  array  $array1
     * @return array
     */
    public function convertEmptyStringsToNull($array)
    {
        foreach ($array as $key => $value) {
            if ($value == '' || $value == 'null') {
                $array[$key] = null;
            }
        }

        return $array;
    }

    /**
     * Create singleton object through single facade.
     *
     * @param  string  $className
     * @return object
     */
    public function getSingletonInstance($className)
    {
        if (array_key_exists($className, $this->singletonInstances)) {
            return $this->singletonInstances[$className];
        }

        return $this->singletonInstances[$className] = app($className);
    }

    /**
     * Returns a string as selector part for identifying elements in views.
     */
    public static function taxRateAsIdentifier(float $taxRate): string
    {
        return str_replace('.', '_', (string) $taxRate);
    }

    /**
     * Create singleton object through single facade.
     *
     * @param  string  $className
     * @return object
     */
    public function getTaxCategoryById($id)
    {
        if (empty($id)) {
            return;
        }

        if (array_key_exists($id, $this->taxCategoriesById)) {
            return $this->taxCategoriesById[$id];
        }

        return $this->taxCategoriesById[$id] = $this->taxCategoryRepository->find($id);
    }

    /**
     * Get sender email details.
     *
     * @return array
     */
    public function getSenderEmailDetails()
    {
        $senderName = $this->getConfigData('emails.configure.email_settings.sender_name') ?: config('mail.from.name');

        $senderEmail = $this->getConfigData('emails.configure.email_settings.shop_email_from') ?: config('mail.from.address');

        return [
            'name'  => $senderName,
            'email' => $senderEmail,
        ];
    }

    /**
     * Get Admin email details.
     *
     * @return array
     */
    public function getAdminEmailDetails()
    {
        $adminName = $this->getConfigData('emails.configure.email_settings.admin_name')
            ?: (config('mail.admin.name')
            ?: config('mail.from.name'));

        $adminEmail = $this->getConfigData('emails.configure.email_settings.admin_email')
            ?: config('mail.admin.address');

        return [
            'name'  => $adminName,
            'email' => $adminEmail,
        ];
    }

    /**
     * Array merge.
     *
     * @return array
     */
    protected function arrayMerge(array &$array1, array &$array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (
                is_array($value)
                && isset($merged[$key])
                && is_array($merged[$key])
            ) {
                $merged[$key] = $this->arrayMerge($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    /**
     * Get core config values.
     *
     * @param  mixed  $field
     * @param  mixed  $channel
     * @param  mixed  $locale
     * @return mixed
     */
    protected function getCoreConfig($field, $channel, $locale)
    {
        $fields = $this->getConfigField($field);

        if (! empty($fields['channel_based'])) {
            if (! empty($fields['locale_based'])) {
                $coreConfigValue = $this->coreConfigRepository->findOneWhere([
                    'code'         => $field,
                    'channel_code' => $channel,
                    'locale_code'  => $locale,
                ]);
            } else {
                $coreConfigValue = $this->coreConfigRepository->findOneWhere([
                    'code'         => $field,
                    'channel_code' => $channel,
                ]);
            }
        } else {
            if (! empty($fields['locale_based'])) {
                $coreConfigValue = $this->coreConfigRepository->findOneWhere([
                    'code'        => $field,
                    'locale_code' => $locale,
                ]);
            } else {
                $coreConfigValue = $this->coreConfigRepository->findOneWhere([
                    'code' => $field,
                ]);
            }
        }

        return $coreConfigValue;
    }

    /**
     * Get default config.
     *
     * @param  string  $field
     * @return mixed
     */
    protected function getDefaultConfig($field)
    {
        $configFieldInfo = $this->getConfigField($field);

        $fields = explode('.', $field);

        array_shift($fields);

        $field = implode('.', $fields);

        return Config::get($field, $configFieldInfo['default'] ?? null);
    }

    /**
     * Get max upload size from the php.ini file.
     *
     * @return string
     */
    public function getMaxUploadSize()
    {
        return ini_get('upload_max_filesize');
    }
}
