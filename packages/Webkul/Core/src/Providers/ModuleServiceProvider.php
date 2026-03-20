<?php

namespace Webkul\Core\Providers;

use Webkul\Core\Models\Channel;
use Webkul\Core\Models\CoreConfig;
use Webkul\Core\Models\Country;
use Webkul\Core\Models\CountryState;
use Webkul\Core\Models\CountryStateTranslation;
use Webkul\Core\Models\CountryTranslation;
use Webkul\Core\Models\Currency;
use Webkul\Core\Models\CurrencyExchangeRate;
use Webkul\Core\Models\Locale;
use Webkul\Core\Models\SubscribersList;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        Channel::class,
        CoreConfig::class,
        Country::class,
        CountryState::class,
        CountryStateTranslation::class,
        CountryTranslation::class,
        Currency::class,
        CurrencyExchangeRate::class,
        Locale::class,
        SubscribersList::class,
    ];
}
