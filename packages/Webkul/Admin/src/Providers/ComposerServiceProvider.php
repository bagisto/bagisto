<?php

namespace Webkul\Admin\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {

        //for the users in the countries dashboard
        View::composer('admin::settings.countries.index', 'Webkul\Admin\Http\ViewComposers\DataGrids\CountryComposer');

        //for the users in the admin dashboard
        View::composer('admin::users.users.index', 'Webkul\Admin\Http\ViewComposers\DataGrids\UserComposer');

        //for the users in the admin dashboard
        View::composer('admin::users.roles.index', 'Webkul\Admin\Http\ViewComposers\DataGrids\RolesComposer');

        //for the locales in admin dashboard
        View::composer('admin::settings.locales.index', 'Webkul\Admin\Http\ViewComposers\DataGrids\LocalesComposer');

        //for the currencies in admin dashboard
        View::composer('admin::settings.currencies.index', 'Webkul\Admin\Http\ViewComposers\DataGrids\CurrenciesComposer');

        //for the Exchange Rates in admin dashboard
        View::composer('admin::settings.exchange_rates.index', 'Webkul\Admin\Http\ViewComposers\DataGrids\ExchangeRatesComposer');

        //for inventory sources in admin dashboard
        View::composer('admin::settings.inventory_sources.index', 'Webkul\Admin\Http\ViewComposers\DataGrids\InventorySourcesComposer');

        //for channels in admin dashboard
        View::composer('admin::settings.channels.index', 'Webkul\Admin\Http\ViewComposers\DataGrids\ChannelsComposer');

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
