<?php

Route::group(['middleware' => ['web']], function () {
    Route::prefix('admin')->group(function () {
        // Login Routes
        Route::get('/login', 'Webkul\User\Http\Controllers\SessionController@create')->defaults('_config', [
            'view' => 'admin::users.sessions.create'
        ])->name('admin.session.create');

        Route::post('/login', 'Webkul\User\Http\Controllers\SessionController@store')->defaults('_config', [
            'redirect' => 'admin.dashboard.index'
        ])->name('admin.session.store');


        // Forget Password Routes
        Route::get('/forget-password', 'Webkul\User\Http\Controllers\ForgetPasswordController@create')->defaults('_config', [
            'view' => 'admin::users.forget-password.create'
        ])->name('admin.forget-password.create');

        Route::post('/forget-password', 'Webkul\User\Http\Controllers\ForgetPasswordController@store')->name('admin.forget-password.store');


        // Reset Password Routes
        Route::get('/reset-password/{token}', 'Webkul\User\Http\Controllers\ResetPasswordController@create')->defaults('_config', [
            'view' => 'admin::users.reset-password.create'
        ])->name('admin.reset-password.create');

        Route::post('/reset-password', 'Webkul\User\Http\Controllers\ResetPasswordController@store')->defaults('_config', [
            'redirect' => 'admin.dashboard.index'
        ])->name('admin.reset-password.store');


        // Admin Routes
        Route::group(['middleware' => ['admin', 'locale']], function () {
            Route::get('/logout', 'Webkul\User\Http\Controllers\SessionController@destroy')->defaults('_config', [
                'redirect' => 'admin.session.create'
            ])->name('admin.session.destroy');


            // Dashboard Route
            Route::get('/dashboard', 'Webkul\Admin\Http\Controllers\DashboardController@index')->name('admin.dashboard.index');


            // Catalog Routes
            Route::prefix('catalog')->group(function () {

                // Catalog Product Routes
                Route::get('/products', 'Webkul\Product\Http\Controllers\ProductController@index')->defaults('_config', [
                    'view' => 'admin::catalog.products.index'
                ])->name('admin.catalog.products.index');

                Route::get('/products/create', 'Webkul\Product\Http\Controllers\ProductController@create')->defaults('_config', [
                    'view' => 'admin::catalog.products.create'
                ])->name('admin.catalog.products.create');

                Route::post('/products/create', 'Webkul\Product\Http\Controllers\ProductController@store')->defaults('_config', [
                    'redirect' => 'admin.catalog.products.edit'
                ])->name('admin.catalog.products.store');

                Route::get('/products/edit/{id}', 'Webkul\Product\Http\Controllers\ProductController@edit')->defaults('_config', [
                    'view' => 'admin::catalog.products.edit'
                ])->name('admin.catalog.products.edit');

                Route::put('/products/edit/{id}', 'Webkul\Product\Http\Controllers\ProductController@update')->defaults('_config', [
                    'redirect' => 'admin.catalog.products.index'
                ])->name('admin.catalog.products.update');


                // Catalog Category Routes
                Route::get('/categories', 'Webkul\Category\Http\Controllers\CategoryController@index')->defaults('_config', [
                    'view' => 'admin::catalog.categories.index'
                ])->name('admin.catalog.categories.index');

                Route::get('/categories/create', 'Webkul\Category\Http\Controllers\CategoryController@create')->defaults('_config', [
                    'view' => 'admin::catalog.categories.create'
                ])->name('admin.catalog.categories.create');

                Route::post('/categories/create', 'Webkul\Category\Http\Controllers\CategoryController@store')->defaults('_config', [
                    'redirect' => 'admin.catalog.categories.index'
                ])->name('admin.catalog.categories.store');

                Route::get('/categories/edit/{id}', 'Webkul\Category\Http\Controllers\CategoryController@edit')->defaults('_config', [
                    'view' => 'admin::catalog.categories.edit'
                ])->name('admin.catalog.categories.edit');

                Route::put('/categories/edit/{id}', 'Webkul\Category\Http\Controllers\CategoryController@update')->defaults('_config', [
                    'redirect' => 'admin.catalog.categories.index'
                ])->name('admin.catalog.categories.update');


                // Catalog Attribute Routes
                Route::get('/attributes', 'Webkul\Attribute\Http\Controllers\AttributeController@index')->defaults('_config', [
                    'view' => 'admin::catalog.attributes.index'
                ])->name('admin.catalog.attributes.index');

                Route::get('/attributes/create', 'Webkul\Attribute\Http\Controllers\AttributeController@create')->defaults('_config', [
                    'view' => 'admin::catalog.attributes.create'
                ])->name('admin.catalog.attributes.create');

                Route::post('/attributes/create', 'Webkul\Attribute\Http\Controllers\AttributeController@store')->defaults('_config', [
                    'redirect' => 'admin.catalog.attributes.index'
                ])->name('admin.catalog.attributes.store');

                Route::get('/attributes/edit/{id}', 'Webkul\Attribute\Http\Controllers\AttributeController@edit')->defaults('_config', [
                    'view' => 'admin::catalog.attributes.edit'
                ])->name('admin.catalog.attributes.edit');

                Route::put('/attributes/edit/{id}', 'Webkul\Attribute\Http\Controllers\AttributeController@update')->defaults('_config', [
                    'redirect' => 'admin.catalog.attributes.index'
                ])->name('admin.catalog.attributes.update');


                // Catalog Family Routes
                Route::get('/families', 'Webkul\Attribute\Http\Controllers\AttributeFamilyController@index')->defaults('_config', [
                    'view' => 'admin::catalog.families.index'
                ])->name('admin.catalog.families.index');

                Route::get('/families/create', 'Webkul\Attribute\Http\Controllers\AttributeFamilyController@create')->defaults('_config', [
                    'view' => 'admin::catalog.families.create'
                ])->name('admin.catalog.families.create');

                Route::post('/families/create', 'Webkul\Attribute\Http\Controllers\AttributeFamilyController@store')->defaults('_config', [
                    'redirect' => 'admin.catalog.families.index'
                ])->name('admin.catalog.families.store');

                Route::get('/families/edit/{id}', 'Webkul\Attribute\Http\Controllers\AttributeFamilyController@edit')->defaults('_config', [
                    'view' => 'admin::catalog.families.edit'
                ])->name('admin.catalog.families.edit');

                Route::put('/families/edit/{id}', 'Webkul\Attribute\Http\Controllers\AttributeFamilyController@update')->defaults('_config', [
                    'redirect' => 'admin.catalog.families.index'
                ])->name('admin.catalog.families.update');
            });


            // Datagrid Routes

            //for datagrid and its loading, filtering, sorting and queries
            Route::get('datagrid', 'Webkul\Admin\Http\Controllers\DataGridController@index')->name('admin.datagrid.index');

            Route::any('datagrid/massaction/delete', 'Webkul\Admin\Http\Controllers\DataGridController@massDelete')->name('admin.datagrid.delete');

            Route::any('datagrid/massaction/edit','Webkul\Admin\Http\Controllers\DataGridController@massUpdate')->name('admin.datagrid.edit');

            // User Routes
            Route::get('/users', 'Webkul\User\Http\Controllers\UserController@index')->defaults('_config', [
                'view' => 'admin::users.users.index'
            ])->name('admin.users.index');

            Route::get('/users/create', 'Webkul\User\Http\Controllers\UserController@create')->defaults('_config', [
                'view' => 'admin::users.users.create'
            ])->name('admin.users.create');

            Route::post('/users/create', 'Webkul\User\Http\Controllers\UserController@store')->defaults('_config', [
                'redirect' => 'admin.users.index'
            ])->name('admin.users.store');

            Route::get('/users/edit/{id}', 'Webkul\User\Http\Controllers\UserController@edit')->defaults('_config', [
                'view' => 'admin::users.users.edit'
            ])->name('admin.users.edit');

            Route::put('/users/edit/{id}', 'Webkul\User\Http\Controllers\UserController@update')->defaults('_config', [
                'redirect' => 'admin.users.index'
            ])->name('admin.users.update');


            // User Role Routes
            Route::get('/roles', 'Webkul\User\Http\Controllers\RoleController@index')->defaults('_config', [
                'view' => 'admin::users.roles.index'
            ])->name('admin.roles.index');

            Route::get('/roles/create', 'Webkul\User\Http\Controllers\RoleController@create')->defaults('_config', [
                'view' => 'admin::users.roles.create'
            ])->name('admin.roles.create');

            Route::post('/roles/create', 'Webkul\User\Http\Controllers\RoleController@store')->defaults('_config', [
                'redirect' => 'admin.roles.index'
            ])->name('admin.roles.store');

            Route::get('/roles/edit/{id}', 'Webkul\User\Http\Controllers\RoleController@edit')->defaults('_config', [
                'view' => 'admin::users.roles.edit'
            ])->name('admin.roles.edit');

            Route::put('/roles/edit/{id}', 'Webkul\User\Http\Controllers\RoleController@update')->defaults('_config', [
                'redirect' => 'admin.roles.index'
            ])->name('admin.roles.update');


            // Locale Routes
            Route::get('/locales', 'Webkul\Core\Http\Controllers\LocaleController@index')->defaults('_config', [
                'view' => 'admin::settings.locales.index'
            ])->name('admin.locales.index');

            Route::get('/locales/create', 'Webkul\Core\Http\Controllers\LocaleController@create')->defaults('_config', [
                'view' => 'admin::settings.locales.create'
            ])->name('admin.locales.create');

            Route::post('/locales/create', 'Webkul\Core\Http\Controllers\LocaleController@store')->defaults('_config', [
                'redirect' => 'admin.locales.index'
            ])->name('admin.locales.store');


            // Country Routes
            Route::get('/countries', 'Webkul\Core\Http\Controllers\CountryController@index')->defaults('_config', [
                'view' => 'admin::settings.countries.index'
            ])->name('admin.countries.index');

            Route::get('/countries/create', 'Webkul\Core\Http\Controllers\CountryController@create')->defaults('_config', [
                'view' => 'admin::settings.countries.create'
            ])->name('admin.countries.create');

            Route::post('/countries/create', 'Webkul\Core\Http\Controllers\CountryController@store')->defaults('_config', [
                'redirect' => 'admin.countries.index'
            ])->name('admin.countries.store');


            // Currency Routes
            Route::get('/currencies', 'Webkul\Core\Http\Controllers\CurrencyController@index')->defaults('_config', [
                'view' => 'admin::settings.currencies.index'
            ])->name('admin.currencies.index');

            Route::get('/currencies/create', 'Webkul\Core\Http\Controllers\CurrencyController@create')->defaults('_config', [
                'view' => 'admin::settings.currencies.create'
            ])->name('admin.currencies.create');

            Route::post('/currencies/create', 'Webkul\Core\Http\Controllers\CurrencyController@store')->defaults('_config', [
                'redirect' => 'admin.currencies.index'
            ])->name('admin.currencies.store');


            // Exchange Rates Routes
            Route::get('/exchange_rates', 'Webkul\Core\Http\Controllers\ExchangeRateController@index')->defaults('_config', [
                'view' => 'admin::settings.exchange_rates.index'
            ])->name('admin.exchange_rates.index');

            Route::get('/exchange_rates/create', 'Webkul\Core\Http\Controllers\ExchangeRateController@create')->defaults('_config', [
                'view' => 'admin::settings.exchange_rates.create'
            ])->name('admin.exchange_rates.create');

            Route::post('/exchange_rates/create', 'Webkul\Core\Http\Controllers\ExchangeRateController@store')->defaults('_config', [
                'redirect' => 'admin.exchange_rates.index'
            ])->name('admin.exchange_rates.store');

            Route::get('/exchange_rates/edit/{id}', 'Webkul\Core\Http\Controllers\ExchangeRateController@edit')->defaults('_config', [
                'view' => 'admin::settings.exchange_rates.edit'
            ])->name('admin.exchange_rates.edit');

            Route::put('/exchange_rates/edit/{id}', 'Webkul\Core\Http\Controllers\ExchangeRateController@update')->defaults('_config', [
                'redirect' => 'admin.exchange_rates.index'
            ])->name('admin.exchange_rates.update');


            // Inventory Source Routes
            Route::get('/inventory_sources', 'Webkul\Inventory\Http\Controllers\InventorySourceController@index')->defaults('_config', [
                'view' => 'admin::settings.inventory_sources.index'
            ])->name('admin.inventory_sources.index');

            Route::get('/inventory_sources/create', 'Webkul\Inventory\Http\Controllers\InventorySourceController@create')->defaults('_config', [
                'view' => 'admin::settings.inventory_sources.create'
            ])->name('admin.inventory_sources.create');

            Route::post('/inventory_sources/create', 'Webkul\Inventory\Http\Controllers\InventorySourceController@store')->defaults('_config', [
                'redirect' => 'admin.inventory_sources.index'
            ])->name('admin.inventory_sources.store');

            Route::get('/inventory_sources/edit/{id}', 'Webkul\Inventory\Http\Controllers\InventorySourceController@edit')->defaults('_config', [
                'view' => 'admin::settings.inventory_sources.edit'
            ])->name('admin.inventory_sources.edit');

            Route::put('/inventory_sources/edit/{id}', 'Webkul\Inventory\Http\Controllers\InventorySourceController@update')->defaults('_config', [
                'redirect' => 'admin.inventory_sources.index'
            ])->name('admin.inventory_sources.update');


            // Channel Routes
            Route::get('/channels', 'Webkul\Core\Http\Controllers\ChannelController@index')->defaults('_config', [
                'view' => 'admin::settings.channels.index'
            ])->name('admin.channels.index');

            Route::get('/channels/create', 'Webkul\Core\Http\Controllers\ChannelController@create')->defaults('_config', [
                'view' => 'admin::settings.channels.create'
            ])->name('admin.channels.create');

            Route::post('/channels/create', 'Webkul\Core\Http\Controllers\ChannelController@store')->defaults('_config', [
                'redirect' => 'admin.channels.index'
            ])->name('admin.channels.store');

            Route::get('/channels/edit/{id}', 'Webkul\Core\Http\Controllers\ChannelController@edit')->defaults('_config', [
                'view' => 'admin::settings.channels.edit'
            ])->name('admin.channels.edit');

            Route::put('/channels/edit/{id}', 'Webkul\Core\Http\Controllers\ChannelController@update')->defaults('_config', [
                'redirect' => 'admin.channels.index'
            ])->name('admin.channels.update');



            // Admin Profile route
            Route::get('/account', 'Webkul\User\Http\Controllers\AccountController@edit')->defaults('_config', [
                'view' => 'admin::account.edit'
            ])->name('admin.account.edit');

            Route::put('/account', 'Webkul\User\Http\Controllers\AccountController@update')->name('admin.account.update');

            // Admin Store Front Settings Route
            Route::get('/slider','Webkul\Shop\Http\Controllers\SliderController@index')->defaults('_config',[
                'view' => 'admin::settings.sliders.index'
            ])->name('admin.sliders.index');

            // Admin Store Front Settings Route
            Route::get('/slider/create','Webkul\Shop\Http\Controllers\SliderController@create')->defaults('_config',[
                'view' => 'admin::settings.sliders.create'
            ])->name('admin.sliders.create');

            Route::post('/slider/create','Webkul\Shop\Http\Controllers\SliderController@store')->defaults('_config',[
                'redirect' => 'admin::sliders.index'
            ])->name('admin.sliders.store');

            //tax routes
            Route::prefix('tax')->group(function () {

                Route::get('taxrule', 'Webkul\Core\Http\Controllers\TaxController@index')->defaults('_config', [
                    'view' => 'admin::tax.taxrule.index'
                ])->name('admin.taxrule.index');

                // tax rule routes
                Route::get('taxrule/create', 'Webkul\Core\Http\Controllers\TaxCategoryController@show')->defaults('_config', [
                    'view' => 'admin::tax.taxrule.create.create'
                ])->name('admin.taxrule.show');

                Route::post('taxrule/create', 'Webkul\Core\Http\Controllers\TaxCategoryController@create')->defaults('_config', [
                    'redirect' => 'admin.taxrule.index'
                ])->name('admin.taxrule.create');

                Route::get('/taxrule/edit/{id}', 'Webkul\Core\Http\Controllers\TaxCategoryController@edit')->defaults('_config', [
                    'view' => 'admin::tax.taxrule.edit.edit'
                ])->name('admin.taxrule.edit');

                Route::put('/taxrule/edit/{id}', 'Webkul\Core\Http\Controllers\TaxCategoryController@update')->defaults('_config', [
                    'redirect' => 'admin.taxrule.index'
                ])->name('admin.taxrule.update');

                //tax rule ends

                //tax rate

                Route::get('taxrate', 'Webkul\Core\Http\Controllers\TaxRateController@index')->defaults('_config', [
                    'view' => 'admin::tax.taxrate.index'
                ])->name('admin.taxrate.index');

                Route::get('taxrate/create', 'Webkul\Core\Http\Controllers\TaxRateController@show')->defaults('_config', [
                    'view' => 'admin::tax.taxrate.create.taxrate'
                ])->name('admin.taxrate.show');

                Route::post('taxrate/create', 'Webkul\Core\Http\Controllers\TaxRateController@create')->defaults('_config', [
                    'redirect' => 'admin.taxrate.index'
                ])->name('admin.taxrate.create');

                Route::get('taxrate/edit/{id}', 'Webkul\Core\Http\Controllers\TaxRateController@edit')->defaults('_config', [
                    'view' => 'admin::tax.taxrate.edit.edit'
                ])->name('admin.taxrate.store');

                Route::put('taxrate/update/{id}', 'Webkul\Core\Http\Controllers\TaxRateController@update')->defaults('_config', [
                    'redirect' => 'admin.taxrate.index'
                ])->name('admin.taxrate.update');
            });
            //tax rate ends
        });
    });
});
