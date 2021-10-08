<?php

// Controllers
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Controllers\ConfigurationController;
use Webkul\Admin\Http\Controllers\Customer\AddressController;
use Webkul\Admin\Http\Controllers\Customer\CustomerController;
use Webkul\Admin\Http\Controllers\Customer\CustomerGroupController;
use Webkul\Admin\Http\Controllers\DashboardController;
use Webkul\Admin\Http\Controllers\ExportController;
use Webkul\Admin\Http\Controllers\Sales\InvoiceController;
use Webkul\Admin\Http\Controllers\Sales\OrderController;
use Webkul\Admin\Http\Controllers\Sales\RefundController;
use Webkul\Admin\Http\Controllers\Sales\ShipmentController;
use Webkul\Admin\Http\Controllers\Sales\TransactionController;
use Webkul\Admin\Http\Controllers\TinyMCEController;
use Webkul\Attribute\Http\Controllers\AttributeController;
use Webkul\Attribute\Http\Controllers\AttributeFamilyController;
use Webkul\CartRule\Http\Controllers\CartRuleController;
use Webkul\CartRule\Http\Controllers\CartRuleCouponController;
use Webkul\CatalogRule\Http\Controllers\CatalogRuleController;
use Webkul\Category\Http\Controllers\CategoryController;
use Webkul\CMS\Http\Controllers\Admin\PageController;
use Webkul\Core\Http\Controllers\ChannelController;
use Webkul\Core\Http\Controllers\CurrencyController;
use Webkul\Core\Http\Controllers\ExchangeRateController;
use Webkul\Core\Http\Controllers\LocaleController;
use Webkul\Core\Http\Controllers\SliderController;
use Webkul\Core\Http\Controllers\SubscriptionController;
use Webkul\Inventory\Http\Controllers\InventorySourceController;
use Webkul\Marketing\Http\Controllers\CampaignController;
use Webkul\Marketing\Http\Controllers\EventController;
use Webkul\Marketing\Http\Controllers\TemplateController;
use Webkul\Product\Http\Controllers\ProductController;
use Webkul\Product\Http\Controllers\ReviewController;
use Webkul\Tax\Http\Controllers\TaxController;
use Webkul\Tax\Http\Controllers\TaxCategoryController;
use Webkul\Tax\Http\Controllers\TaxRateController;
use Webkul\User\Http\Controllers\AccountController;
use Webkul\User\Http\Controllers\ForgetPasswordController;
use Webkul\User\Http\Controllers\ResetPasswordController;
use Webkul\User\Http\Controllers\RoleController;
use Webkul\User\Http\Controllers\SessionController;
use Webkul\User\Http\Controllers\UserController;

Route::group(['middleware' => ['web', 'admin_locale']], function () {
    Route::prefix(config('app.admin_url'))->group(function () {

        Route::get('/', [Controller::class, 'redirectToLogin']);

        // Login Routes
        Route::get('/login', [SessionController::class, 'create'])->defaults('_config', [
            'view' => 'admin::users.sessions.create',
        ])->name('admin.session.create');

        //login post route to admin auth controller
        Route::post('/login', [SessionController::class, 'store'])->defaults('_config', [
            'redirect' => 'admin.dashboard.index',
        ])->name('admin.session.store');

        // Forget Password Routes
        Route::get('/forget-password', [ForgetPasswordController::class, 'create'])->defaults('_config', [
            'view' => 'admin::users.forget-password.create',
        ])->name('admin.forget-password.create');

        Route::post('/forget-password', [ForgetPasswordController::class, 'store'])->name('admin.forget-password.store');

        // Reset Password Routes
        Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->defaults('_config', [
            'view' => 'admin::users.reset-password.create',
        ])->name('admin.reset-password.create');

        Route::post('/reset-password', [ResetPasswordController::class, 'store'])->defaults('_config', [
            'redirect' => 'admin.dashboard.index',
        ])->name('admin.reset-password.store');


        // Admin Routes
        Route::group(['middleware' => ['admin']], function () {
            Route::get('/logout', [SessionController::class, 'destroy'])->defaults('_config', [
                'redirect' => 'admin.session.create',
            ])->name('admin.session.destroy');

            /**
             * Tinymce file upload handler.
             */
            Route::post('tinymce/upload', [TinyMCEController::class, 'upload'])
                ->name('admin.tinymce.upload');

            // Dashboard Route
            Route::get('dashboard', [DashboardController::class, 'index'])->defaults('_config', [
                'view' => 'admin::dashboard.index',
            ])->name('admin.dashboard.index');

            // Customer Management Routes
            Route::get('customers', [CustomerController::class, 'index'])->defaults('_config', [
                'view' => 'admin::customers.index',
            ])->name('admin.customer.index');

            Route::get('customers/create', [CustomerController::class, 'create'])->defaults('_config', [
                'view' => 'admin::customers.create',
            ])->name('admin.customer.create');

            Route::post('customers/create', [CustomerController::class, 'store'])->defaults('_config', [
                'redirect' => 'admin.customer.index',
            ])->name('admin.customer.store');

            Route::get('customers/edit/{id}', [CustomerController::class, 'edit'])->defaults('_config', [
                'view' => 'admin::customers.edit',
            ])->name('admin.customer.edit');

            Route::get('customers/note/{id}', [CustomerController::class, 'createNote'])->defaults('_config', [
                'view' => 'admin::customers.note',
            ])->name('admin.customer.note.create');

            Route::put('customers/note/{id}', [CustomerController::class, 'storeNote'])->defaults('_config', [
                'redirect' => 'admin.customer.index',
            ])->name('admin.customer.note.store');

            Route::put('customers/edit/{id}', [CustomerController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.customer.index',
            ])->name('admin.customer.update');

            Route::post('customers/delete/{id}', [CustomerController::class, 'destroy'])->name('admin.customer.delete');

            Route::post('customers/masssdelete', [CustomerController::class, 'massDestroy'])->name('admin.customer.mass-delete');

            Route::post('customers/masssupdate', [CustomerController::class, 'massUpdate'])->name('admin.customer.mass-update');

            Route::get('reviews', [ReviewController::class, 'index'])->defaults('_config', [
                'view' => 'admin::customers.reviews.index',
            ])->name('admin.customer.review.index');

            // Customer's addresses routes
            Route::get('customers/{id}/addresses', [AddressController::class, 'index'])->defaults('_config', [
                'view' => 'admin::customers.addresses.index',
            ])->name('admin.customer.addresses.index');

            Route::get('customers/{id}/addresses/create', [AddressController::class, 'create'])->defaults('_config', [
                'view' => 'admin::customers.addresses.create',
            ])->name('admin.customer.addresses.create');

            Route::post('customers/{id}/addresses/create', [AddressController::class, 'store'])->defaults('_config', [
                'redirect' => 'admin.customer.addresses.index',
            ])->name('admin.customer.addresses.store');

            Route::get('customers/addresses/edit/{id}', [AddressController::class, 'edit'])->defaults('_config', [
                'view' => 'admin::customers.addresses.edit',
            ])->name('admin.customer.addresses.edit');

            Route::put('customers/addresses/edit/{id}', [AddressController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.customer.addresses.index',
            ])->name('admin.customer.addresses.update');

            Route::post('customers/addresses/delete/{id}', [AddressController::class, 'destroy'])->defaults('_config', [
                'redirect' => 'admin.customer.addresses.index',
            ])->name('admin.customer.addresses.delete');

            //mass destroy
            Route::post('customers/{id}/addresses', [AddressController::class, 'massDestroy'])->defaults('_config', [
                'redirect' => 'admin.customer.addresses.index',
            ])->name('admin.customer.addresses.massdelete');

            // Customer's invoices route
            Route::get('customers/{id}/invoices', [CustomerController::class, 'invoices'])->name('admin.customer.invoices.data');

            // Customer's orders route
            Route::get('customers/{id}/orders', [CustomerController::class, 'orders'])->defaults('_config', [
                'view' => 'admin::customers.orders.index',
            ])->name('admin.customer.orders.data');

            // Configuration routes
            Route::get('configuration/{slug?}/{slug2?}', [ConfigurationController::class, 'index'])->defaults('_config', [
                'view' => 'admin::configuration.index',
            ])->name('admin.configuration.index');

            Route::post('configuration/{slug?}/{slug2?}', [ConfigurationController::class, 'store'])->defaults('_config', [
                'redirect' => 'admin.configuration.index',
            ])->name('admin.configuration.index.store');

            Route::get('configuration/{slug?}/{slug2?}/{path}', [ConfigurationController::class, 'download'])->defaults('_config', [
                'redirect' => 'admin.configuration.index',
            ])->name('admin.configuration.download');

            // Reviews Routes
            Route::get('reviews/edit/{id}', [ReviewController::class, 'edit'])->defaults('_config', [
                'view' => 'admin::customers.reviews.edit',
            ])->name('admin.customer.review.edit');

            Route::put('reviews/edit/{id}', [ReviewController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.customer.review.index',
            ])->name('admin.customer.review.update');

            Route::post('reviews/delete/{id}', [ReviewController::class, 'destroy'])->defaults('_config', [
                'redirect' => 'admin.customer.review.index',
            ])->name('admin.customer.review.delete');

            //mass destroy
            Route::post('reviews/massdestroy', [ReviewController::class, 'massDestroy'])->defaults('_config', [
                'redirect' => 'admin.customer.review.index',
            ])->name('admin.customer.review.massdelete');

            //mass update
            Route::post('reviews/massupdate', [ReviewController::class, 'massUpdate'])->defaults('_config', [
                'redirect' => 'admin.customer.review.index',
            ])->name('admin.customer.review.massupdate');

            // Customer Groups Routes
            Route::get('groups', [CustomerGroupController::class, 'index'])->defaults('_config', [
                'view' => 'admin::customers.groups.index',
            ])->name('admin.groups.index');

            Route::get('groups/create', [CustomerGroupController::class, 'create'])->defaults('_config', [
                'view' => 'admin::customers.groups.create',
            ])->name('admin.groups.create');

            Route::post('groups/create', [CustomerGroupController::class, 'store'])->defaults('_config', [
                'redirect' => 'admin.groups.index',
            ])->name('admin.groups.store');

            Route::get('groups/edit/{id}', [CustomerGroupController::class, 'edit'])->defaults('_config', [
                'view' => 'admin::customers.groups.edit',
            ])->name('admin.groups.edit');

            Route::put('groups/edit/{id}', [CustomerGroupController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.groups.index',
            ])->name('admin.groups.update');

            Route::post('groups/delete/{id}', [CustomerGroupController::class, 'destroy'])->name('admin.groups.delete');


            // Sales Routes
            Route::prefix('sales')->group(function () {
                // Sales Order Routes
                Route::get('/orders', [OrderController::class, 'index'])->defaults('_config', [
                    'view' => 'admin::sales.orders.index',
                ])->name('admin.sales.orders.index');

                Route::get('/orders/view/{id}', [OrderController::class, 'view'])->defaults('_config', [
                    'view' => 'admin::sales.orders.view',
                ])->name('admin.sales.orders.view');

                Route::get('/orders/cancel/{id}', [OrderController::class, 'cancel'])->defaults('_config', [
                    'view' => 'admin::sales.orders.cancel',
                ])->name('admin.sales.orders.cancel');

                Route::post('/orders/create/{order_id}', [OrderController::class, 'comment'])->name('admin.sales.orders.comment');


                // Sales Invoices Routes
                Route::get('/invoices', [InvoiceController::class, 'index'])->defaults('_config', [
                    'view' => 'admin::sales.invoices.index',
                ])->name('admin.sales.invoices.index');

                Route::get('/invoices/create/{order_id}', [InvoiceController::class, 'create'])->defaults('_config', [
                    'view' => 'admin::sales.invoices.create',
                ])->name('admin.sales.invoices.create');

                Route::post('/invoices/create/{order_id}', [InvoiceController::class, 'store'])->defaults('_config', [
                    'redirect' => 'admin.sales.orders.view',
                ])->name('admin.sales.invoices.store');

                Route::get('/invoices/view/{id}', [InvoiceController::class, 'view'])->defaults('_config', [
                    'view' => 'admin::sales.invoices.view',
                ])->name('admin.sales.invoices.view');

                Route::get('/invoices/print/{id}', [InvoiceController::class, 'print'])->defaults('_config', [
                    'view' => 'admin::sales.invoices.print',
                ])->name('admin.sales.invoices.print');


                // Sales Shipments Routes
                Route::get('/shipments', [ShipmentController::class, 'index'])->defaults('_config', [
                    'view' => 'admin::sales.shipments.index',
                ])->name('admin.sales.shipments.index');

                Route::get('/shipments/create/{order_id}', [ShipmentController::class, 'create'])->defaults('_config', [
                    'view' => 'admin::sales.shipments.create',
                ])->name('admin.sales.shipments.create');

                Route::post('/shipments/create/{order_id}', [ShipmentController::class, 'store'])->defaults('_config', [
                    'redirect' => 'admin.sales.orders.view',
                ])->name('admin.sales.shipments.store');

                Route::get('/shipments/view/{id}', [ShipmentController::class, 'view'])->defaults('_config', [
                    'view' => 'admin::sales.shipments.view',
                ])->name('admin.sales.shipments.view');


                // Sales Redunds Routes
                Route::get('/refunds', [RefundController::class, 'index'])->defaults('_config', [
                    'view' => 'admin::sales.refunds.index',
                ])->name('admin.sales.refunds.index');

                Route::get('/refunds/create/{order_id}', [RefundController::class, 'create'])->defaults('_config', [
                    'view' => 'admin::sales.refunds.create',
                ])->name('admin.sales.refunds.create');

                Route::post('/refunds/create/{order_id}', [RefundController::class, 'store'])->defaults('_config', [
                    'redirect' => 'admin.sales.orders.view',
                ])->name('admin.sales.refunds.store');

                Route::post('/refunds/update-qty/{order_id}', [RefundController::class, 'updateQty'])->defaults('_config', [
                    'redirect' => 'admin.sales.orders.view',
                ])->name('admin.sales.refunds.update_qty');

                Route::get('/refunds/view/{id}', [RefundController::class, 'view'])->defaults('_config', [
                    'view' => 'admin::sales.refunds.view',
                ])->name('admin.sales.refunds.view');

                // Sales Transactions Routes
                Route::get('/transactions', [TransactionController::class, 'index'])->defaults('_config', [
                    'view' => 'admin::sales.transactions.index',
                ])->name('admin.sales.transactions.index');

                Route::get('/transactions/create', [TransactionController::class, 'create'])->defaults('_config', [
                    'view' => 'admin::sales.transactions.create',
                ])->name('admin.sales.transactions.create');

                Route::post('/transactions/create', [TransactionController::class, 'store'])->name('admin.sales.transactions.store');

                Route::get('/transactions/view/{id}', [TransactionController::class, 'view'])->defaults('_config', [
                    'view' => 'admin::sales.transactions.view',
                ])->name('admin.sales.transactions.view');
            });

            // Catalog Routes
            Route::prefix('catalog')->group(function () {
                Route::get('/sync', [ProductController::class, 'sync']);

                // Catalog Product Routes
                Route::get('/products', [ProductController::class, 'index'])->defaults('_config', [
                    'view' => 'admin::catalog.products.index',
                ])->name('admin.catalog.products.index');

                Route::get('/products/create', [ProductController::class, 'create'])->defaults('_config', [
                    'view' => 'admin::catalog.products.create',
                ])->name('admin.catalog.products.create');

                Route::post('/products/create', [ProductController::class, 'store'])->defaults('_config', [
                    'redirect' => 'admin.catalog.products.edit',
                ])->name('admin.catalog.products.store');

                Route::get('products/copy/{id}', [ProductController::class, 'copy'])->defaults('_config', [
                    'view' => 'admin::catalog.products.edit',
                ])->name('admin.catalog.products.copy');

                Route::get('/products/edit/{id}', [ProductController::class, 'edit'])->defaults('_config', [
                    'view' => 'admin::catalog.products.edit',
                ])->name('admin.catalog.products.edit');

                Route::put('/products/edit/{id}', [ProductController::class, 'update'])->defaults('_config', [
                    'redirect' => 'admin.catalog.products.index',
                ])->name('admin.catalog.products.update');

                Route::put('/products/edit/{id}/inventories', [ProductController::class, 'updateInventories'])->defaults('_config', [
                    'redirect' => 'admin.catalog.products.index',
                ])->name('admin.catalog.products.update-inventories');

                Route::post('/products/upload-file/{id}', [ProductController::class, 'uploadLink'])->name('admin.catalog.products.upload_link');

                Route::post('/products/upload-sample/{id}', [ProductController::class, 'uploadSample'])->name('admin.catalog.products.upload_sample');

                //product delete
                Route::post('/products/delete/{id}', [ProductController::class, 'destroy'])->name('admin.catalog.products.delete');

                //product massaction
                Route::post('products/massaction', [ProductController::class, 'massActionHandler'])->name('admin.catalog.products.massaction');

                //product massdelete
                Route::post('products/massdelete', [ProductController::class, 'massDestroy'])->defaults('_config', [
                    'redirect' => 'admin.catalog.products.index',
                ])->name('admin.catalog.products.massdelete');

                //product massupdate
                Route::post('products/massupdate', [ProductController::class, 'massUpdate'])->defaults('_config', [
                    'redirect' => 'admin.catalog.products.index',
                ])->name('admin.catalog.products.massupdate');

                //product search for linked products
                Route::get('products/search', [ProductController::class, 'productLinkSearch'])->defaults('_config', [
                    'view' => 'admin::catalog.products.edit',
                ])->name('admin.catalog.products.productlinksearch');

                Route::get('products/search-simple-products', [ProductController::class, 'searchSimpleProducts'])->name('admin.catalog.products.search_simple_product');

                Route::get('/products/{id}/{attribute_id}', [ProductController::class, 'download'])->defaults('_config', [
                    'view' => 'admin.catalog.products.edit',
                ])->name('admin.catalog.products.file.download');

                // Catalog Category Routes
                Route::get('/categories', [CategoryController::class, 'index'])->defaults('_config', [
                    'view' => 'admin::catalog.categories.index',
                ])->name('admin.catalog.categories.index');

                Route::get('/categories/create', [CategoryController::class, 'create'])->defaults('_config', [
                    'view' => 'admin::catalog.categories.create',
                ])->name('admin.catalog.categories.create');

                Route::post('/categories/create', [CategoryController::class, 'store'])->defaults('_config', [
                    'redirect' => 'admin.catalog.categories.index',
                ])->name('admin.catalog.categories.store');

                Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->defaults('_config', [
                    'view' => 'admin::catalog.categories.edit',
                ])->name('admin.catalog.categories.edit');

                Route::put('/categories/edit/{id}', [CategoryController::class, 'update'])->defaults('_config', [
                    'redirect' => 'admin.catalog.categories.index',
                ])->name('admin.catalog.categories.update');

                Route::post('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('admin.catalog.categories.delete');

                //category massdelete
                Route::post('categories/massdelete', [CategoryController::class, 'massDestroy'])->defaults('_config', [
                    'redirect' => 'admin.catalog.categories.index',
                ])->name('admin.catalog.categories.massdelete');

                Route::post('/categories/product/count', [CategoryController::class, 'categoryProductCount'])->name('admin.catalog.categories.product.count');


                // Catalog Attribute Routes
                Route::get('/attributes', [AttributeController::class, 'index'])->defaults('_config', [
                    'view' => 'admin::catalog.attributes.index',
                ])->name('admin.catalog.attributes.index');

                Route::get('/attributes/{id}/options', [AttributeController::class, 'getAttributeOptions'])->defaults('_config', [
                    'view' => 'admin::catalog.attributes.options',
                ])->name('admin.catalog.attributes.options');

                Route::get('/attributes/create', [AttributeController::class, 'create'])->defaults('_config', [
                    'view' => 'admin::catalog.attributes.create',
                ])->name('admin.catalog.attributes.create');

                Route::post('/attributes/create', [AttributeController::class, 'store'])->defaults('_config', [
                    'redirect' => 'admin.catalog.attributes.index',
                ])->name('admin.catalog.attributes.store');

                Route::get('/attributes/edit/{id}', [AttributeController::class, 'edit'])->defaults('_config', [
                    'view' => 'admin::catalog.attributes.edit',
                ])->name('admin.catalog.attributes.edit');

                Route::put('/attributes/edit/{id}', [AttributeController::class, 'update'])->defaults('_config', [
                    'redirect' => 'admin.catalog.attributes.index',
                ])->name('admin.catalog.attributes.update');

                Route::post('/attributes/delete/{id}', [AttributeController::class, 'destroy'])->name('admin.catalog.attributes.delete');

                Route::post('/attributes/massdelete', [AttributeController::class, 'massDestroy'])->name('admin.catalog.attributes.massdelete');

                // Catalog Family Routes
                Route::get('/families', [AttributeFamilyController::class, 'index'])->defaults('_config', [
                    'view' => 'admin::catalog.families.index',
                ])->name('admin.catalog.families.index');

                Route::get('/families/create', [AttributeFamilyController::class, 'create'])->defaults('_config', [
                    'view' => 'admin::catalog.families.create',
                ])->name('admin.catalog.families.create');

                Route::post('/families/create', [AttributeFamilyController::class, 'store'])->defaults('_config', [
                    'redirect' => 'admin.catalog.families.index',
                ])->name('admin.catalog.families.store');

                Route::get('/families/edit/{id}', [AttributeFamilyController::class, 'edit'])->defaults('_config', [
                    'view' => 'admin::catalog.families.edit',
                ])->name('admin.catalog.families.edit');

                Route::put('/families/edit/{id}', [AttributeFamilyController::class, 'update'])->defaults('_config', [
                    'redirect' => 'admin.catalog.families.index',
                ])->name('admin.catalog.families.update');

                Route::post('/families/delete/{id}', [AttributeFamilyController::class, 'destroy'])->name('admin.catalog.families.delete');
            });

            // User Routes
            //datagrid for backend users
            Route::get('/users', [UserController::class, 'index'])->defaults('_config', [
                'view' => 'admin::users.users.index',
            ])->name('admin.users.index');

            //create backend user get
            Route::get('/users/create', [UserController::class, 'create'])->defaults('_config', [
                'view' => 'admin::users.users.create',
            ])->name('admin.users.create');

            //create backend user post
            Route::post('/users/create', [UserController::class, 'store'])->defaults('_config', [
                'redirect' => 'admin.users.index',
            ])->name('admin.users.store');

            //delete backend user view
            Route::get('/users/edit/{id}', [UserController::class, 'edit'])->defaults('_config', [
                'view' => 'admin::users.users.edit',
            ])->name('admin.users.edit');

            //edit backend user submit
            Route::put('/users/edit/{id}', [UserController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.users.index',
            ])->name('admin.users.update');

            //delete backend user
            Route::post('/users/delete/{id}', [UserController::class, 'destroy'])->name('admin.users.delete');

            Route::get('/users/confirm/{id}', [UserController::class, 'confirm'])->defaults('_config', [
                'view' => 'admin::customers.confirm-password',
            ])->name('super.users.confirm');

            Route::post('/users/confirm/{id}', [UserController::class, 'destroySelf'])->defaults('_config', [
                'redirect' => 'admin.users.index',
            ])->name('admin.users.destroy');

            // User Role Routes
            Route::get('/roles', [RoleController::class, 'index'])->defaults('_config', [
                'view' => 'admin::users.roles.index',
            ])->name('admin.roles.index');

            Route::get('/roles/create', [RoleController::class, 'create'])->defaults('_config', [
                'view' => 'admin::users.roles.create',
            ])->name('admin.roles.create');

            Route::post('/roles/create', [RoleController::class, 'store'])->defaults('_config', [
                'redirect' => 'admin.roles.index',
            ])->name('admin.roles.store');

            Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])->defaults('_config', [
                'view' => 'admin::users.roles.edit',
            ])->name('admin.roles.edit');

            Route::put('/roles/edit/{id}', [RoleController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.roles.index',
            ])->name('admin.roles.update');

            Route::post('/roles/delete/{id}', [RoleController::class, 'destroy'])->name('admin.roles.delete');


            // Locale Routes
            Route::get('/locales', [LocaleController::class, 'index'])->defaults('_config', [
                'view' => 'admin::settings.locales.index',
            ])->name('admin.locales.index');

            Route::get('/locales/create', [LocaleController::class, 'create'])->defaults('_config', [
                'view' => 'admin::settings.locales.create',
            ])->name('admin.locales.create');

            Route::post('/locales/create', [LocaleController::class, 'store'])->defaults('_config', [
                'redirect' => 'admin.locales.index',
            ])->name('admin.locales.store');

            Route::get('/locales/edit/{id}', [LocaleController::class, 'edit'])->defaults('_config', [
                'view' => 'admin::settings.locales.edit',
            ])->name('admin.locales.edit');

            Route::put('/locales/edit/{id}', [LocaleController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.locales.index',
            ])->name('admin.locales.update');

            Route::post('/locales/delete/{id}', [LocaleController::class, 'destroy'])->name('admin.locales.delete');


            // Currency Routes
            Route::get('/currencies', [CurrencyController::class, 'index'])->defaults('_config', [
                'view' => 'admin::settings.currencies.index',
            ])->name('admin.currencies.index');

            Route::get('/currencies/create', [CurrencyController::class, 'create'])->defaults('_config', [
                'view' => 'admin::settings.currencies.create',
            ])->name('admin.currencies.create');

            Route::post('/currencies/create', [CurrencyController::class, 'store'])->defaults('_config', [
                'redirect' => 'admin.currencies.index',
            ])->name('admin.currencies.store');

            Route::get('/currencies/edit/{id}', [CurrencyController::class, 'edit'])->defaults('_config', [
                'view' => 'admin::settings.currencies.edit',
            ])->name('admin.currencies.edit');

            Route::put('/currencies/edit/{id}', [CurrencyController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.currencies.index',
            ])->name('admin.currencies.update');

            Route::post('/currencies/delete/{id}', [CurrencyController::class, 'destroy'])->name('admin.currencies.delete');

            Route::post('/currencies/massdelete', [CurrencyController::class, 'massDestroy'])->name('admin.currencies.massdelete');


            // Exchange Rates Routes
            Route::get('/exchange_rates', [ExchangeRateController::class, 'index'])->defaults('_config', [
                'view' => 'admin::settings.exchange_rates.index',
            ])->name('admin.exchange_rates.index');

            Route::get('/exchange_rates/create', [ExchangeRateController::class, 'create'])->defaults('_config', [
                'view' => 'admin::settings.exchange_rates.create',
            ])->name('admin.exchange_rates.create');

            Route::post('/exchange_rates/create', [ExchangeRateController::class, 'store'])->defaults('_config', [
                'redirect' => 'admin.exchange_rates.index',
            ])->name('admin.exchange_rates.store');

            Route::get('/exchange_rates/edit/{id}', [ExchangeRateController::class, 'edit'])->defaults('_config', [
                'view' => 'admin::settings.exchange_rates.edit',
            ])->name('admin.exchange_rates.edit');

            Route::get('/exchange_rates/update-rates', [ExchangeRateController::class, 'updateRates'])->name('admin.exchange_rates.update_rates');

            Route::put('/exchange_rates/edit/{id}', [ExchangeRateController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.exchange_rates.index',
            ])->name('admin.exchange_rates.update');

            Route::post('/exchange_rates/delete/{id}', [ExchangeRateController::class, 'destroy'])->name('admin.exchange_rates.delete');


            // Inventory Source Routes
            Route::get('/inventory_sources', [InventorySourceController::class, 'index'])->defaults('_config', [
                'view' => 'admin::settings.inventory_sources.index',
            ])->name('admin.inventory_sources.index');

            Route::get('/inventory_sources/create', [InventorySourceController::class, 'create'])->defaults('_config', [
                'view' => 'admin::settings.inventory_sources.create',
            ])->name('admin.inventory_sources.create');

            Route::post('/inventory_sources/create', [InventorySourceController::class, 'store'])->defaults('_config', [
                'redirect' => 'admin.inventory_sources.index',
            ])->name('admin.inventory_sources.store');

            Route::get('/inventory_sources/edit/{id}', [InventorySourceController::class, 'edit'])->defaults('_config', [
                'view' => 'admin::settings.inventory_sources.edit',
            ])->name('admin.inventory_sources.edit');

            Route::put('/inventory_sources/edit/{id}', [InventorySourceController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.inventory_sources.index',
            ])->name('admin.inventory_sources.update');

            Route::post('/inventory_sources/delete/{id}', [InventorySourceController::class, 'destroy'])->name('admin.inventory_sources.delete');

            // Channel Routes
            Route::get('/channels', [ChannelController::class, 'index'])->defaults('_config', [
                'view' => 'admin::settings.channels.index',
            ])->name('admin.channels.index');

            Route::get('/channels/create', [ChannelController::class, 'create'])->defaults('_config', [
                'view' => 'admin::settings.channels.create',
            ])->name('admin.channels.create');

            Route::post('/channels/create', [ChannelController::class, 'store'])->defaults('_config', [
                'redirect' => 'admin.channels.index',
            ])->name('admin.channels.store');

            Route::get('/channels/edit/{id}', [ChannelController::class, 'edit'])->defaults('_config', [
                'view' => 'admin::settings.channels.edit',
            ])->name('admin.channels.edit');

            Route::put('/channels/edit/{id}', [ChannelController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.channels.index',
            ])->name('admin.channels.update');

            Route::post('/channels/delete/{id}', [ChannelController::class, 'destroy'])->name('admin.channels.delete');


            // Admin Profile route
            Route::get('/account', [AccountController::class, 'edit'])->defaults('_config', [
                'view' => 'admin::account.edit',
            ])->name('admin.account.edit');

            Route::put('/account', [AccountController::class, 'update'])->name('admin.account.update');

            //slider index
            Route::get('/slider', [SliderController::class, 'index'])->defaults('_config', [
                'view' => 'admin::settings.sliders.index',
            ])->name('admin.sliders.index');

            //slider create show
            Route::get('slider/create', [SliderController::class, 'create'])->defaults('_config', [
                'view' => 'admin::settings.sliders.create',
            ])->name('admin.sliders.create');

            //slider create show
            Route::post('slider/create', [SliderController::class, 'store'])->defaults('_config', [
                'redirect' => 'admin.sliders.index',
            ])->name('admin.sliders.store');

            //slider edit show
            Route::get('slider/edit/{id}', [SliderController::class, 'edit'])->defaults('_config', [
                'view' => 'admin::settings.sliders.edit',
            ])->name('admin.sliders.edit');

            //slider edit update
            Route::post('slider/edit/{id}', [SliderController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.sliders.index',
            ])->name('admin.sliders.update');

            //destroy a slider item
            Route::post('slider/delete/{id}', [SliderController::class, 'destroy'])->name('admin.sliders.delete');

            //tax routes
            Route::get('/tax-categories', [TaxController::class, 'index'])->defaults('_config', [
                'view' => 'admin::tax.tax-categories.index',
            ])->name('admin.tax-categories.index');


            // tax category routes
            Route::get('/tax-categories/create', [TaxCategoryController::class, 'show'])->defaults('_config', [
                'view' => 'admin::tax.tax-categories.create',
            ])->name('admin.tax-categories.create');

            Route::post('/tax-categories/create', [TaxCategoryController::class, 'create'])->defaults('_config', [
                'redirect' => 'admin.tax-categories.index',
            ])->name('admin.tax-categories.store');

            Route::get('/tax-categories/edit/{id}', [TaxCategoryController::class, 'edit'])->defaults('_config', [
                'view' => 'admin::tax.tax-categories.edit',
            ])->name('admin.tax-categories.edit');

            Route::put('/tax-categories/edit/{id}', [TaxCategoryController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.tax-categories.index',
            ])->name('admin.tax-categories.update');

            Route::post('/tax-categories/delete/{id}', [TaxCategoryController::class, 'destroy'])->name('admin.tax-categories.delete');
            //tax category ends


            //tax rate
            Route::get('tax-rates', [TaxRateController::class, 'index'])->defaults('_config', [
                'view' => 'admin::tax.tax-rates.index',
            ])->name('admin.tax-rates.index');

            Route::get('tax-rates/create', [TaxRateController::class, 'show'])->defaults('_config', [
                'view' => 'admin::tax.tax-rates.create',
            ])->name('admin.tax-rates.create');

            Route::post('tax-rates/create', [TaxRateController::class, 'create'])->defaults('_config', [
                'redirect' => 'admin.tax-rates.index',
            ])->name('admin.tax-rates.store');

            Route::get('tax-rates/edit/{id}', [TaxRateController::class, 'edit'])->defaults('_config', [
                'view' => 'admin::tax.tax-rates.edit',
            ])->name('admin.tax-rates.edit');

            Route::put('tax-rates/update/{id}', [TaxRateController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.tax-rates.index',
            ])->name('admin.tax-rates.update');

            Route::post('/tax-rates/delete/{id}', [TaxRateController::class, 'destroy'])->name('admin.tax-rates.delete');

            Route::post('/tax-rates/import', [TaxRateController::class, 'import'])->defaults('_config', [
                'redirect' => 'admin.tax-rates.index',
            ])->name('admin.tax-rates.import');
            //tax rate ends

            //DataGrid Export
            Route::post(config('app.admin_url') . '/export', [ExportController::class, 'export'])->name('admin.datagrid.export');

            Route::prefix('promotions')->group(function () {
                Route::get('cart-rules', [CartRuleController::class, 'index'])->defaults('_config', [
                    'view' => 'admin::marketing.promotions.cart-rules.index',
                ])->name('admin.cart-rules.index');

                Route::get('cart-rules/create', [CartRuleController::class, 'create'])->defaults('_config', [
                    'view' => 'admin::marketing.promotions.cart-rules.create',
                ])->name('admin.cart-rules.create');

                Route::post('cart-rules/create', [CartRuleController::class, 'store'])->defaults('_config', [
                    'redirect' => 'admin.cart-rules.index',
                ])->name('admin.cart-rules.store');

                Route::get('cart-rules/copy/{id}', [CartRuleController::class, 'copy'])->defaults('_config', [
                    'view' => 'admin::marketing.promotions.cart-rules.edit',
                ])->name('admin.cart-rules.copy');

                Route::get('cart-rules/edit/{id}', [CartRuleController::class, 'edit'])->defaults('_config', [
                    'view' => 'admin::marketing.promotions.cart-rules.edit',
                ])->name('admin.cart-rules.edit');

                Route::post('cart-rules/edit/{id}', [CartRuleController::class, 'update'])->defaults('_config', [
                    'redirect' => 'admin.cart-rules.index',
                ])->name('admin.cart-rules.update');

                Route::post('cart-rules/delete/{id}', [CartRuleController::class, 'destroy'])->name('admin.cart-rules.delete');

                Route::post('cart-rules/generate-coupons/{id?}', [CartRuleController::class, 'generateCoupons'])->name('admin.cart-rules.generate-coupons');

                Route::post('/massdelete', [CartRuleCouponController::class, 'massDelete'])->name('admin.cart-rule-coupons.mass-delete');


                //Catalog rules
                Route::get('catalog-rules', [CatalogRuleController::class, 'index'])->defaults('_config', [
                    'view' => 'admin::marketing.promotions.catalog-rules.index',
                ])->name('admin.catalog-rules.index');

                Route::get('catalog-rules/create', [CatalogRuleController::class, 'create'])->defaults('_config', [
                    'view' => 'admin::marketing.promotions.catalog-rules.create',
                ])->name('admin.catalog-rules.create');

                Route::post('catalog-rules/create', [CatalogRuleController::class, 'store'])->defaults('_config', [
                    'redirect' => 'admin.catalog-rules.index',
                ])->name('admin.catalog-rules.store');

                Route::get('catalog-rules/edit/{id}', [CatalogRuleController::class, 'edit'])->defaults('_config', [
                    'view' => 'admin::marketing.promotions.catalog-rules.edit',
                ])->name('admin.catalog-rules.edit');

                Route::post('catalog-rules/edit/{id}', [CatalogRuleController::class, 'update'])->defaults('_config', [
                    'redirect' => 'admin.catalog-rules.index',
                ])->name('admin.catalog-rules.update');

                Route::post('catalog-rules/delete/{id}', [CatalogRuleController::class, 'destroy'])->name('admin.catalog-rules.delete');


                //Marketing campaigns routes
                Route::get('campaigns', [CampaignController::class, 'index'])->defaults('_config', [
                    'view' => 'admin::marketing.email-marketing.campaigns.index',
                ])->name('admin.campaigns.index');

                Route::get('campaigns/create', [CampaignController::class, 'create'])->defaults('_config', [
                    'view' => 'admin::marketing.email-marketing.campaigns.create',
                ])->name('admin.campaigns.create');

                Route::post('campaigns/create', [CampaignController::class, 'store'])->defaults('_config', [
                    'redirect' => 'admin.campaigns.index',
                ])->name('admin.campaigns.store');

                Route::get('campaigns/edit/{id}', [CampaignController::class, 'edit'])->defaults('_config', [
                    'view' => 'admin::marketing.email-marketing.campaigns.edit',
                ])->name('admin.campaigns.edit');

                Route::post('campaigns/edit/{id}', [CampaignController::class, 'update'])->defaults('_config', [
                    'redirect' => 'admin.campaigns.index',
                ])->name('admin.campaigns.update');

                Route::post('campaigns/delete/{id}', [CampaignController::class, 'destroy'])->name('admin.campaigns.delete');


                //Marketing emails templates routes
                Route::get('email-templates', [TemplateController::class, 'index'])->defaults('_config', [
                    'view' => 'admin::marketing.email-marketing.templates.index',
                ])->name('admin.email-templates.index');

                Route::get('email-templates/create', [TemplateController::class, 'create'])->defaults('_config', [
                    'view' => 'admin::marketing.email-marketing.templates.create',
                ])->name('admin.email-templates.create');

                Route::post('email-templates/create', [TemplateController::class, 'store'])->defaults('_config', [
                    'redirect' => 'admin.email-templates.index',
                ])->name('admin.email-templates.store');

                Route::get('email-templates/edit/{id}', [TemplateController::class, 'edit'])->defaults('_config', [
                    'view' => 'admin::marketing.email-marketing.templates.edit',
                ])->name('admin.email-templates.edit');

                Route::post('email-templates/edit/{id}', [TemplateController::class, 'update'])->defaults('_config', [
                    'redirect' => 'admin.email-templates.index',
                ])->name('admin.email-templates.update');

                Route::post('email-templates/delete/{id}', [TemplateController::class, 'destroy'])->name('admin.email-templates.delete');


                //Marketing events routes
                Route::get('events', [EventController::class, 'index'])->defaults('_config', [
                    'view' => 'admin::marketing.email-marketing.events.index',
                ])->name('admin.events.index');

                Route::get('events/create', [EventController::class, 'create'])->defaults('_config', [
                    'view' => 'admin::marketing.email-marketing.events.create',
                ])->name('admin.events.create');

                Route::post('events/create', [EventController::class, 'store'])->defaults('_config', [
                    'redirect' => 'admin.events.index',
                ])->name('admin.events.store');

                Route::get('events/edit/{id}', [EventController::class, 'edit'])->defaults('_config', [
                    'view' => 'admin::marketing.email-marketing.events.edit',
                ])->name('admin.events.edit');

                Route::post('events/edit/{id}', [EventController::class, 'update'])->defaults('_config', [
                    'redirect' => 'admin.events.index',
                ])->name('admin.events.update');

                Route::post('events/delete/{id}', [EventController::class, 'destroy'])->name('admin.events.delete');


                // Admin Store Front Settings Route
                Route::get('/subscribers', [SubscriptionController::class, 'index'])->defaults('_config', [
                    'view' => 'admin::marketing.email-marketing.subscribers.index',
                ])->name('admin.customers.subscribers.index');

                //destroy a newsletter subscription item
                Route::post('subscribers/delete/{id}', [SubscriptionController::class, 'destroy'])->name('admin.customers.subscribers.delete');

                Route::get('subscribers/edit/{id}', [SubscriptionController::class, 'edit'])->defaults('_config', [
                    'view' => 'admin::marketing.email-marketing.subscribers.edit',
                ])->name('admin.customers.subscribers.edit');

                Route::put('subscribers/update/{id}', [SubscriptionController::class, 'update'])->defaults('_config', [
                    'redirect' => 'admin.customers.subscribers.index',
                ])->name('admin.customers.subscribers.update');
            });

            Route::prefix('cms')->group(function () {
                Route::get('/', [PageController::class, 'index'])->defaults('_config', [
                    'view' => 'admin::cms.index',
                ])->name('admin.cms.index');


                Route::get('create', [PageController::class, 'create'])->defaults('_config', [
                    'view' => 'admin::cms.create',
                ])->name('admin.cms.create');

                Route::post('create', [PageController::class, 'store'])->defaults('_config', [
                    'redirect' => 'admin.cms.index',
                ])->name('admin.cms.store');

                Route::get('edit/{id}', [PageController::class, 'edit'])->defaults('_config', [
                    'view' => 'admin::cms.edit',
                ])->name('admin.cms.edit');

                Route::post('edit/{id}', [PageController::class, 'update'])->defaults('_config', [
                    'redirect' => 'admin.cms.index',
                ])->name('admin.cms.update');

                Route::post('/delete/{id}', [PageController::class, 'delete'])->defaults('_config', [
                    'redirect' => 'admin.cms.index',
                ])->name('admin.cms.delete');

                Route::post('/massdelete', [PageController::class, 'massDelete'])->defaults('_config', [
                    'redirect' => 'admin.cms.index',
                ])->name('admin.cms.mass-delete');

                // Route::post('/delete/{id}', 'Webkul\CMS\Http\Controllers\Admin\PageController@delete')->defaults('_config', [
                //     'redirect' => 'admin.cms.index'
                // ])->name('admin.cms.delete');
            });
        });
    });
});
