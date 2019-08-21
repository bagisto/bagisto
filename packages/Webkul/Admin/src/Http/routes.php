<?php

Route::group(['middleware' => ['web']], function () {
    Route::prefix('admin')->group(function () {

        Route::get('/', 'Webkul\Admin\Http\Controllers\Controller@redirectToLogin');

        // Login Routes
        Route::get('/login', 'Webkul\User\Http\Controllers\SessionController@create')->defaults('_config', [
            'view' => 'admin::users.sessions.create'
        ])->name('admin.session.create');

        //login post route to admin auth controller
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
        Route::group(['middleware' => ['admin']], function () {
            Route::get('/logout', 'Webkul\User\Http\Controllers\SessionController@destroy')->defaults('_config', [
                'redirect' => 'admin.session.create'
            ])->name('admin.session.destroy');

            // Dashboard Route
            Route::get('dashboard', 'Webkul\Admin\Http\Controllers\DashboardController@index')->defaults('_config', [
                'view' => 'admin::dashboard.index'
            ])->name('admin.dashboard.index');

            //Customer Management Routes
            Route::get('customers', 'Webkul\Admin\Http\Controllers\Customer\CustomerController@index')->defaults('_config', [
                'view' => 'admin::customers.index'
            ])->name('admin.customer.index');

            Route::get('customers/create', 'Webkul\Admin\Http\Controllers\Customer\CustomerController@create')->defaults('_config',[
                'view' => 'admin::customers.create'
            ])->name('admin.customer.create');

            Route::post('customers/create', 'Webkul\Admin\Http\Controllers\Customer\CustomerController@store')->defaults('_config',[
                'redirect' => 'admin.customer.index'
            ])->name('admin.customer.store');

            Route::get('customers/edit/{id}', 'Webkul\Admin\Http\Controllers\Customer\CustomerController@edit')->defaults('_config',[
                'view' => 'admin::customers.edit'
            ])->name('admin.customer.edit');

            Route::get('customers/note/{id}', 'Webkul\Admin\Http\Controllers\Customer\CustomerController@createNote')->defaults('_config',[
                'view' => 'admin::customers.note'
            ])->name('admin.customer.note.create');

            Route::put('customers/note/{id}', 'Webkul\Admin\Http\Controllers\Customer\CustomerController@storeNote')->defaults('_config',[
                'redirect' => 'admin.customer.index'
            ])->name('admin.customer.note.store');

            Route::put('customers/edit/{id}', 'Webkul\Admin\Http\Controllers\Customer\CustomerController@update')->defaults('_config', [
                'redirect' => 'admin.customer.index'
            ])->name('admin.customer.update');

            Route::post('customers/delete/{id}', 'Webkul\Admin\Http\Controllers\Customer\CustomerController@destroy')->name('admin.customer.delete');

            Route::post('customers/masssdelete', 'Webkul\Admin\Http\Controllers\Customer\CustomerController@massDestroy')->name('admin.customer.mass-delete');

            Route::post('customers/masssupdate', 'Webkul\Admin\Http\Controllers\Customer\CustomerController@massUpdate')->name('admin.customer.mass-update');

            Route::get('reviews', 'Webkul\Product\Http\Controllers\ReviewController@index')->defaults('_config',[
                'view' => 'admin::customers.reviews.index'
            ])->name('admin.customer.review.index');

            // Configuration routes
            Route::get('configuration/{slug?}/{slug2?}', 'Webkul\Admin\Http\Controllers\ConfigurationController@index')->defaults('_config', [
                'view' => 'admin::configuration.index'
            ])->name('admin.configuration.index');

            Route::post('configuration/{slug?}/{slug2?}', 'Webkul\Admin\Http\Controllers\ConfigurationController@store')->defaults('_config', [
                'redirect' => 'admin.configuration.index'
            ])->name('admin.configuration.index.store');

            Route::get('configuration/{slug?}/{slug2?}/{path}', 'Webkul\Admin\Http\Controllers\ConfigurationController@download')->defaults('_config', [
                'redirect' => 'admin.configuration.index'
            ])->name('admin.configuration.download');

            // Reviews Routes
            Route::get('reviews/edit/{id}', 'Webkul\Product\Http\Controllers\ReviewController@edit')->defaults('_config',[
                'view' => 'admin::customers.reviews.edit'
            ])->name('admin.customer.review.edit');

            Route::put('reviews/edit/{id}', 'Webkul\Product\Http\Controllers\ReviewController@update')->defaults('_config', [
                'redirect' => 'admin.customer.review.index'
            ])->name('admin.customer.review.update');

            Route::post('reviews/delete/{id}', 'Webkul\Product\Http\Controllers\ReviewController@destroy')->defaults('_config', [
                'redirect' => 'admin.customer.review.index'
            ])->name('admin.customer.review.delete');

            //mass destroy
            Route::post('reviews/massdestroy', 'Webkul\Product\Http\Controllers\ReviewController@massDestroy')->defaults('_config', [
                'redirect' => 'admin.customer.review.index'
            ])->name('admin.customer.review.massdelete');

            //mass update
            Route::post('reviews/massupdate', 'Webkul\Product\Http\Controllers\ReviewController@massUpdate')->defaults('_config', [
                'redirect' => 'admin.customer.review.index'
            ])->name('admin.customer.review.massupdate');

            // Customer Groups Routes
            Route::get('groups', 'Webkul\Admin\Http\Controllers\Customer\CustomerGroupController@index')->defaults('_config',[
                'view' => 'admin::customers.groups.index'
            ])->name('admin.groups.index');

            Route::get('groups/create', 'Webkul\Admin\Http\Controllers\Customer\CustomerGroupController@create')->defaults('_config',[
                'view' => 'admin::customers.groups.create'
            ])->name('admin.groups.create');

            Route::post('groups/create', 'Webkul\Admin\Http\Controllers\Customer\CustomerGroupController@store')->defaults('_config',[
                'redirect' => 'admin.groups.index'
            ])->name('admin.groups.store');

            Route::get('groups/edit/{id}', 'Webkul\Admin\Http\Controllers\Customer\CustomerGroupController@edit')->defaults('_config',[
                'view' => 'admin::customers.groups.edit'
            ])->name('admin.groups.edit');

            Route::put('groups/edit/{id}', 'Webkul\Admin\Http\Controllers\Customer\CustomerGroupController@update')->defaults('_config',[
                'redirect' => 'admin.groups.index'
            ])->name('admin.groups.update');

            Route::post('groups/delete/{id}', 'Webkul\Admin\Http\Controllers\Customer\CustomerGroupController@destroy')->name('admin.groups.delete');


            // Sales Routes
            Route::prefix('sales')->group(function () {
                // Sales Order Routes
                Route::get('/orders', 'Webkul\Admin\Http\Controllers\Sales\OrderController@index')->defaults('_config', [
                    'view' => 'admin::sales.orders.index'
                ])->name('admin.sales.orders.index');

                Route::get('/orders/view/{id}', 'Webkul\Admin\Http\Controllers\Sales\OrderController@view')->defaults('_config', [
                    'view' => 'admin::sales.orders.view'
                ])->name('admin.sales.orders.view');

                Route::get('/orders/cancel/{id}', 'Webkul\Admin\Http\Controllers\Sales\OrderController@cancel')->defaults('_config', [
                    'view' => 'admin::sales.orders.cancel'
                ])->name('admin.sales.orders.cancel');


                // Sales Invoices Routes
                Route::get('/invoices', 'Webkul\Admin\Http\Controllers\Sales\InvoiceController@index')->defaults('_config', [
                    'view' => 'admin::sales.invoices.index'
                ])->name('admin.sales.invoices.index');

                Route::get('/invoices/create/{order_id}', 'Webkul\Admin\Http\Controllers\Sales\InvoiceController@create')->defaults('_config', [
                    'view' => 'admin::sales.invoices.create'
                ])->name('admin.sales.invoices.create');

                Route::post('/invoices/create/{order_id}', 'Webkul\Admin\Http\Controllers\Sales\InvoiceController@store')->defaults('_config', [
                    'redirect' => 'admin.sales.orders.view'
                ])->name('admin.sales.invoices.store');

                Route::get('/invoices/view/{id}', 'Webkul\Admin\Http\Controllers\Sales\InvoiceController@view')->defaults('_config', [
                    'view' => 'admin::sales.invoices.view'
                ])->name('admin.sales.invoices.view');

                Route::get('/invoices/print/{id}', 'Webkul\Admin\Http\Controllers\Sales\InvoiceController@print')->defaults('_config', [
                    'view' => 'admin::sales.invoices.print'
                ])->name('admin.sales.invoices.print');


                // Sales Shipments Routes
                Route::get('/shipments', 'Webkul\Admin\Http\Controllers\Sales\ShipmentController@index')->defaults('_config', [
                    'view' => 'admin::sales.shipments.index'
                ])->name('admin.sales.shipments.index');

                Route::get('/shipments/create/{order_id}', 'Webkul\Admin\Http\Controllers\Sales\ShipmentController@create')->defaults('_config', [
                    'view' => 'admin::sales.shipments.create'
                ])->name('admin.sales.shipments.create');

                Route::post('/shipments/create/{order_id}', 'Webkul\Admin\Http\Controllers\Sales\ShipmentController@store')->defaults('_config', [
                    'redirect' => 'admin.sales.orders.view'
                ])->name('admin.sales.shipments.store');

                Route::get('/shipments/view/{id}', 'Webkul\Admin\Http\Controllers\Sales\ShipmentController@view')->defaults('_config', [
                    'view' => 'admin::sales.shipments.view'
                ])->name('admin.sales.shipments.view');
            });

            // Catalog Routes
            Route::prefix('catalog')->group(function () {
                Route::get('/sync', 'Webkul\Product\Http\Controllers\ProductController@sync');

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

                Route::post('/products/upload-file/{id}', 'Webkul\Product\Http\Controllers\ProductController@uploadLink')->name('admin.catalog.products.upload_link');

                Route::post('/products/upload-sample/{id}', 'Webkul\Product\Http\Controllers\ProductController@uploadSample')->name('admin.catalog.products.upload_sample');
                
                //product delete
                Route::post('/products/delete/{id}', 'Webkul\Product\Http\Controllers\ProductController@destroy')->name('admin.catalog.products.delete');

                //product massaction
                Route::post('products/massaction', 'Webkul\Product\Http\Controllers\ProductController@massActionHandler')->name('admin.catalog.products.massaction');

                //product massdelete
                Route::post('products/massdelete', 'Webkul\Product\Http\Controllers\ProductController@massDestroy')->defaults('_config', [
                    'redirect' => 'admin.catalog.products.index'
                ])->name('admin.catalog.products.massdelete');

                //product massupdate
                Route::post('products/massupdate', 'Webkul\Product\Http\Controllers\ProductController@massUpdate')->defaults('_config', [
                    'redirect' => 'admin.catalog.products.index'
                ])->name('admin.catalog.products.massupdate');

                //product search for linked products
                Route::get('products/search', 'Webkul\Product\Http\Controllers\ProductController@productLinkSearch')->defaults('_config', [
                    'view' => 'admin::catalog.products.edit'
                ])->name('admin.catalog.products.productlinksearch');

                Route::get('products/search-simple-products', 'Webkul\Product\Http\Controllers\ProductController@searchSimpleProducts')->name('admin.catalog.products.search_simple_product');

                Route::get('/products/{id}/{attribute_id}', 'Webkul\Product\Http\Controllers\ProductController@download')->defaults('_config', [
                    'view' => 'admin.catalog.products.edit'
                ])->name('admin.catalog.products.file.download');

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

                Route::post('/categories/delete/{id}', 'Webkul\Category\Http\Controllers\CategoryController@destroy')->name('admin.catalog.categories.delete');


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

                Route::post('/attributes/delete/{id}', 'Webkul\Attribute\Http\Controllers\AttributeController@destroy')->name('admin.catalog.attributes.delete');

                Route::post('/attributes/massdelete', 'Webkul\Attribute\Http\Controllers\AttributeController@massDestroy')->name('admin.catalog.attributes.massdelete');

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

                Route::post('/families/delete/{id}', 'Webkul\Attribute\Http\Controllers\AttributeFamilyController@destroy')->name('admin.catalog.families.delete');
            });

            // User Routes
            //datagrid for backend users
            Route::get('/users', 'Webkul\User\Http\Controllers\UserController@index')->defaults('_config', [
                'view' => 'admin::users.users.index'
            ])->name('admin.users.index');

            //create backend user get
            Route::get('/users/create', 'Webkul\User\Http\Controllers\UserController@create')->defaults('_config', [
                'view' => 'admin::users.users.create'
            ])->name('admin.users.create');

            //create backend user post
            Route::post('/users/create', 'Webkul\User\Http\Controllers\UserController@store')->defaults('_config', [
                'redirect' => 'admin.users.index'
            ])->name('admin.users.store');

            //delete backend user view
            Route::get('/users/edit/{id}', 'Webkul\User\Http\Controllers\UserController@edit')->defaults('_config', [
                'view' => 'admin::users.users.edit'
            ])->name('admin.users.edit');

            //edit backend user submit
            Route::put('/users/edit/{id}', 'Webkul\User\Http\Controllers\UserController@update')->defaults('_config', [
                'redirect' => 'admin.users.index'
            ])->name('admin.users.update');

            //delete backend user
            Route::post('/users/delete/{id}', 'Webkul\User\Http\Controllers\UserController@destroy')->name('admin.users.delete');

            Route::post('/confirm/destroy', 'Webkul\User\Http\Controllers\UserController@destroySelf')->defaults('_config', [
                'redirect' => 'admin.users.index'
            ])->name('admin.users.confirm.destroy');

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

            Route::post('/roles/delete/{id}', 'Webkul\User\Http\Controllers\RoleController@destroy')->name('admin.roles.delete');


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

            Route::get('/locales/edit/{id}', 'Webkul\Core\Http\Controllers\LocaleController@edit')->defaults('_config', [
                'view' => 'admin::settings.locales.edit'
            ])->name('admin.locales.edit');

            Route::put('/locales/edit/{id}', 'Webkul\Core\Http\Controllers\LocaleController@update')->defaults('_config', [
                'redirect' => 'admin.locales.index'
            ])->name('admin.locales.update');

            Route::post('/locales/delete/{id}', 'Webkul\Core\Http\Controllers\LocaleController@destroy')->name('admin.locales.delete');


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

            Route::get('/currencies/edit/{id}', 'Webkul\Core\Http\Controllers\CurrencyController@edit')->defaults('_config', [
                'view' => 'admin::settings.currencies.edit'
            ])->name('admin.currencies.edit');

            Route::put('/currencies/edit/{id}', 'Webkul\Core\Http\Controllers\CurrencyController@update')->defaults('_config', [
                'redirect' => 'admin.currencies.index'
            ])->name('admin.currencies.update');

            Route::post('/currencies/delete/{id}', 'Webkul\Core\Http\Controllers\CurrencyController@destroy')->name('admin.currencies.delete');

            Route::post('/currencies/massdelete', 'Webkul\Core\Http\Controllers\CurrencyController@massDestroy')->name('admin.currencies.massdelete');


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

            Route::get('/exchange_rates/update-rates/{service}', 'Webkul\Core\Http\Controllers\ExchangeRateController@updateRates')->name('admin.exchange_rates.update-rates');

            Route::put('/exchange_rates/edit/{id}', 'Webkul\Core\Http\Controllers\ExchangeRateController@update')->defaults('_config', [
                'redirect' => 'admin.exchange_rates.index'
            ])->name('admin.exchange_rates.update');

            Route::post('/exchange_rates/delete/{id}', 'Webkul\Core\Http\Controllers\ExchangeRateController@destroy')->name('admin.exchange_rates.delete');


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

            Route::post('/inventory_sources/delete/{id}', 'Webkul\Inventory\Http\Controllers\InventorySourceController@destroy')->name('admin.inventory_sources.delete');

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

            Route::post('/channels/delete/{id}', 'Webkul\Core\Http\Controllers\ChannelController@destroy')->name('admin.channels.delete');


            // Admin Profile route
            Route::get('/account', 'Webkul\User\Http\Controllers\AccountController@edit')->defaults('_config', [
                'view' => 'admin::account.edit'
            ])->name('admin.account.edit');

            Route::put('/account', 'Webkul\User\Http\Controllers\AccountController@update')->name('admin.account.update');


            // Admin Store Front Settings Route
            Route::get('/subscribers','Webkul\Core\Http\Controllers\SubscriptionController@index')->defaults('_config',[
                'view' => 'admin::customers.subscribers.index'
            ])->name('admin.customers.subscribers.index');

            //destroy a newsletter subscription item
            Route::post('subscribers/delete/{id}', 'Webkul\Core\Http\Controllers\SubscriptionController@destroy')->name('admin.customers.subscribers.delete');

            Route::get('subscribers/edit/{id}', 'Webkul\Core\Http\Controllers\SubscriptionController@edit')->defaults('_config', [
                'view' => 'admin::customers.subscribers.edit'
            ])->name('admin.customers.subscribers.edit');

            Route::put('subscribers/update/{id}', 'Webkul\Core\Http\Controllers\SubscriptionController@update')->defaults('_config', [
                'redirect' => 'admin.customers.subscribers.index'
            ])->name('admin.customers.subscribers.update');

            //slider index
            Route::get('/slider','Webkul\Core\Http\Controllers\SliderController@index')->defaults('_config',[
                'view' => 'admin::settings.sliders.index'
            ])->name('admin.sliders.index');

            //slider create show
            Route::get('slider/create','Webkul\Core\Http\Controllers\SliderController@create')->defaults('_config',[
                'view' => 'admin::settings.sliders.create'
            ])->name('admin.sliders.create');

            //slider create show
            Route::post('slider/create','Webkul\Core\Http\Controllers\SliderController@store')->defaults('_config',[
                'redirect' => 'admin.sliders.index'
            ])->name('admin.sliders.store');

            //slider edit show
            Route::get('slider/edit/{id}','Webkul\Core\Http\Controllers\SliderController@edit')->defaults('_config',[
                'view' => 'admin::settings.sliders.edit'
            ])->name('admin.sliders.edit');

            //slider edit update
            Route::post('slider/edit/{id}','Webkul\Core\Http\Controllers\SliderController@update')->defaults('_config',[
                'redirect' => 'admin.sliders.index'
            ])->name('admin.sliders.update');

            //destroy a slider item
            Route::post('slider/delete/{id}', 'Webkul\Core\Http\Controllers\SliderController@destroy')->name('admin.sliders.delete');

            //tax routes
            Route::get('/tax-categories', 'Webkul\Tax\Http\Controllers\TaxController@index')->defaults('_config', [
                'view' => 'admin::tax.tax-categories.index'
            ])->name('admin.tax-categories.index');


            // tax category routes
            Route::get('/tax-categories/create', 'Webkul\Tax\Http\Controllers\TaxCategoryController@show')->defaults('_config', [
                'view' => 'admin::tax.tax-categories.create'
            ])->name('admin.tax-categories.show');

            Route::post('/tax-categories/create', 'Webkul\Tax\Http\Controllers\TaxCategoryController@create')->defaults('_config', [
                'redirect' => 'admin.tax-categories.index'
            ])->name('admin.tax-categories.create');

            Route::get('/tax-categories/edit/{id}', 'Webkul\Tax\Http\Controllers\TaxCategoryController@edit')->defaults('_config', [
                'view' => 'admin::tax.tax-categories.edit'
            ])->name('admin.tax-categories.edit');

            Route::put('/tax-categories/edit/{id}', 'Webkul\Tax\Http\Controllers\TaxCategoryController@update')->defaults('_config', [
                'redirect' => 'admin.tax-categories.index'
            ])->name('admin.tax-categories.update');

            Route::post('/tax-categories/delete/{id}', 'Webkul\Tax\Http\Controllers\TaxCategoryController@destroy')->name('admin.tax-categories.delete');
            //tax category ends


            //tax rate
            Route::get('tax-rates', 'Webkul\Tax\Http\Controllers\TaxRateController@index')->defaults('_config', [
                'view' => 'admin::tax.tax-rates.index'
            ])->name('admin.tax-rates.index');

            Route::get('tax-rates/create', 'Webkul\Tax\Http\Controllers\TaxRateController@show')->defaults('_config', [
                'view' => 'admin::tax.tax-rates.create'
            ])->name('admin.tax-rates.show');

            Route::post('tax-rates/create', 'Webkul\Tax\Http\Controllers\TaxRateController@create')->defaults('_config', [
                'redirect' => 'admin.tax-rates.index'
            ])->name('admin.tax-rates.create');

            Route::get('tax-rates/edit/{id}', 'Webkul\Tax\Http\Controllers\TaxRateController@edit')->defaults('_config', [
                'view' => 'admin::tax.tax-rates.edit'
            ])->name('admin.tax-rates.store');

            Route::put('tax-rates/update/{id}', 'Webkul\Tax\Http\Controllers\TaxRateController@update')->defaults('_config', [
                'redirect' => 'admin.tax-rates.index'
            ])->name('admin.tax-rates.update');

            Route::post('/tax-rates/delete/{id}', 'Webkul\Tax\Http\Controllers\TaxRateController@destroy')->name('admin.tax-rates.delete');

            Route::post('/tax-rates/import', 'Webkul\Tax\Http\Controllers\TaxRateController@import')->defaults('_config', [
                'redirect' => 'admin.tax-rates.index'
            ])->name('admin.tax-rates.import');
            //tax rate ends

            //DataGrid Export
            Route::post('admin/export', 'Webkul\Admin\Http\Controllers\ExportController@export')->name('admin.datagrid.export');

            Route::prefix('promotion')->group(function () {
                Route::get('/catalog-rules', 'Webkul\Discount\Http\Controllers\CatalogRuleController@index')->defaults('_config', [
                    'view' => 'admin::promotions.catalog-rule.index'
                ])->name('admin.catalog-rule.index');

                Route::get('/catalog-rules/create', 'Webkul\Discount\Http\Controllers\CatalogRuleController@create')->defaults('_config', [
                    'view' => 'admin::promotions.catalog-rule.create'
                ])->name('admin.catalog-rule.create');

                Route::post('/catalog-rules/create', 'Webkul\Discount\Http\Controllers\CatalogRuleController@store')->defaults('_config', [
                    'redirect' => 'admin.catalog-rule.index'
                ])->name('admin.catalog-rule.store');

                Route::get('/catalog-rules/edit/{id}', 'Webkul\Discount\Http\Controllers\CatalogRuleController@edit')->defaults('_config', [
                    'view' => 'admin::promotions.catalog-rule.edit'
                ])->name('admin.catalog-rule.edit');

                Route::post('/catalog-rules/edit/{id}', 'Webkul\Discount\Http\Controllers\CatalogRuleController@update')->defaults('_config', [
                    'redirect' => 'admin.catalog-rule.index'
                ])->name('admin.catalog-rule.update');

                Route::get('/catalog-rules/apply', 'Webkul\Discount\Http\Controllers\CatalogRuleController@applyRules')->defaults('_config', [
                    'view' => 'admin::promotions.catalog-rule.index'
                ])->name('admin.catalog-rule.apply');

                Route::post('/catalog-rules/delete/{id}', 'Webkul\Discount\Http\Controllers\CatalogRuleController@destroy')->name('admin.catalog-rule.delete');

                Route::get('/catalog-rules/declutter', 'Webkul\Discount\Http\Controllers\CatalogRuleController@deClutter')->defaults('_config', [
                    'redirect' => 'admin.catalog-rule.index'
                ])->name('admin.catalog-rule.declut');

                Route::post('fetch/options', 'Webkul\Discount\Http\Controllers\CatalogRuleController@fetchAttributeOptions')->name('admin.catalog-rule.options');

                Route::get('cart-rules', 'Webkul\Discount\Http\Controllers\CartRuleController@index')->defaults('_config', [
                    'view' => 'admin::promotions.cart-rule.index'
                ])->name('admin.cart-rule.index');

                Route::get('cart-rules/create', 'Webkul\Discount\Http\Controllers\CartRuleController@create')->defaults('_config', [
                    'view' => 'admin::promotions.cart-rule.create'
                ])->name('admin.cart-rule.create');

                Route::post('cart-rules/store', 'Webkul\Discount\Http\Controllers\CartRuleController@store')->defaults('_config', [
                    'redirect' => 'admin.cart-rule.index'
                ])->name('admin.cart-rule.store');

                Route::get('cart-rules/edit/{id}', 'Webkul\Discount\Http\Controllers\CartRuleController@edit')->defaults('_config', [
                    'view' => 'admin::promotions.cart-rule.edit'
                ])->name('admin.cart-rule.edit');

                Route::post('cart-rules/update/{id}', 'Webkul\Discount\Http\Controllers\CartRuleController@update')->defaults('_config', [
                    'redirect' => 'admin.cart-rule.index'
                ])->name('admin.cart-rule.update');

                Route::post('cart-rules/delete/{id}', 'Webkul\Discount\Http\Controllers\CartRuleController@destroy')->name('admin.cart-rule.delete');
            });

            Route::prefix('cms')->group(function () {
                Route::get('/', 'Webkul\CMS\Http\Controllers\Admin\PageController@index')->defaults('_config', [
                    'view' => 'admin::cms.index'
                ])->name('admin.cms.index');

                Route::get('preview/{url_key}', 'Webkul\CMS\Http\Controllers\Admin\PageController@preview')->name('admin.cms.preview');

                Route::get('create', 'Webkul\CMS\Http\Controllers\Admin\PageController@create')->defaults('_config', [
                    'view' => 'admin::cms.create'
                ])->name('admin.cms.create');

                Route::post('create', 'Webkul\CMS\Http\Controllers\Admin\PageController@store')->defaults('_config', [
                    'redirect' => 'admin.cms.index'
                ])->name('admin.cms.store');

                Route::get('update/{id}', 'Webkul\CMS\Http\Controllers\Admin\PageController@edit')->defaults('_config', [
                    'view' => 'admin::cms.edit'
                ])->name('admin.cms.edit');

                Route::post('update/{id}', 'Webkul\CMS\Http\Controllers\Admin\PageController@update')->defaults('_config', [
                    'redirect' => 'admin.cms.index'
                ])->name('admin.cms.update');

                Route::post('/delete/{id}', 'Webkul\CMS\Http\Controllers\Admin\PageController@delete')->defaults('_config', [
                    'redirect' => 'admin.cms.index'
                ])->name('admin.cms.delete');

                Route::post('/massdelete', 'Webkul\CMS\Http\Controllers\Admin\PageController@massDelete')->defaults('_config', [
                    'redirect' => 'admin.cms.index'
                ])->name('admin.cms.mass-delete');

                // Route::post('/delete/{id}', 'Webkul\CMS\Http\Controllers\Admin\PageController@delete')->defaults('_config', [
                //     'redirect' => 'admin.cms.index'
                // ])->name('admin.cms.delete');
            });
        });
    });
});