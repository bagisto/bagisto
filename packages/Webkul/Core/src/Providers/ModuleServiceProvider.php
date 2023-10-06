<?php

namespace Webkul\Core\Providers;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\Core\Models\Channel::class,
        \Webkul\Core\Models\CoreConfig::class,
        \Webkul\Core\Models\Country::class,
        \Webkul\Core\Models\CountryTranslation::class,
        \Webkul\Core\Models\CountryState::class,
        \Webkul\Core\Models\CountryStateTranslation::class,
        \Webkul\Core\Models\Currency::class,
        \Webkul\Core\Models\CurrencyExchangeRate::class,
        \Webkul\Core\Models\Locale::class,
        \Webkul\Core\Models\SubscribersList::class,
        \Webkul\Core\Models\Visit::class,
    ];
}