<?php

namespace Webkul\SAASCustomizer\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Webkul\SAASCustomizer\Providers\EventServiceProvider;
use Webkul\Sales\Providers\ModuleServiceProvider;
use Webkul\SAASCustomizer\Company;
use Webkul\SAASCustomizer\Http\Middleware\RedirectIfNotSuperAdmin;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Webkul\SAASCustomizer\Exceptions\Handler;
use Webkul\SAASCustomizer\Facades\Company as CompanyFacade;
use Webkul\Core\Tree;

class SAASCustomizerServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Webkul\SAASCustomizer\Commands\Console\GenerateSU'
    ];

    public function boot(Router $router)
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'saas');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'saas');

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('vendor/webkul/saas/assets'),
        ], 'public');

        $router->aliasMiddleware('super-admin', RedirectIfNotSuperAdmin::class);

        //over ride system's default validation
        $this->registerPresenceVerifier();

        //over ride system's default validation DB presence verifier
        $this->registerValidationFactory();

        //model observer for all the core models of Bagisto
        $this->bootModelObservers();

        //over ride all existing core models of Bagisto
        $this->overrideModels();

        $this->app->bind(
            ExceptionHandler::class,
            Handler::class
        );

        $this->composeView();

        $this->app->register(EventServiceProvider::class);

        $this->app->register(ModuleServiceProvider::class);
    }

    /**
     * Compose View
     */
    public function composeView()
    {
        view()->composer(['saas::companies.layouts.nav-left', 'saas::companies.layouts.nav-aside'], function ($view) {
            $tree = Tree::create();

            foreach (config('menu.super-admin') as $index => $item) {
                $tree->add($item, 'menu');
            }

            $tree->items = core()->sortItems($tree->items);

            $view->with('menu', $tree);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        $this->registerFacades();

        //override DB facade
        $this->app->singleton('db', function ($app) {
            return new \Webkul\SAASCustomizer\Database\DatabaseManager($app, $app['db.factory']);
        });

        $this->commands($this->commands);
    }

    public function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/purge-pool.php', 'purge-pool'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/super-menu.php', 'menu.super-admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/excluded-sites.php', 'excluded-sites'
        );
    }

    /**
     * Register the validation factory.
     *
     * @return void
     */
    protected function registerValidationFactory()
    {
        $this->app->singleton('validator', function ($app) {
            $validator = new \Illuminate\Validation\Factory($app['translator'], $app);

            // The validation presence verifier is responsible for determining the existence of
            // values in a given data collection which is typically a relational database or
            // other persistent data stores. It is used to check for "uniqueness" as well.
            if (isset($app['db'], $app['validation.presence'])) {
                $validator->setPresenceVerifier($app['validation.presence']);
            }

            return $validator;
        });
    }

    /**
     * Register the database presence verifier.
     *
     * @return void
     */
    protected function registerPresenceVerifier()
    {
        $this->app->singleton('validation.presence', function ($app) {
            return new \Webkul\SAASCustomizer\Validation\DatabasePresenceVerifier($app['db']);
        });
    }


    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerFacades()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('company', CompanyFacade::class);

        $this->app->singleton('company', function () {
            return app()->make(Company::class);
        });
    }

    /**
     * Override the existing models
     */
    public function overrideModels()
    {
        // Attribute Models Starts
        $this->app->concord->registerModel(\Webkul\Attribute\Contracts\Attribute::class, \Webkul\SAASCustomizer\Models\Attribute\Attribute::class);
        $this->app->concord->registerModel(\Webkul\Attribute\Contracts\AttributeFamily::class, \Webkul\SAASCustomizer\Models\Attribute\AttributeFamily::class);
        $this->app->concord->registerModel(\Webkul\Attribute\Contracts\AttributeGroup::class, \Webkul\SAASCustomizer\Models\Attribute\AttributeGroup::class);
        $this->app->concord->registerModel(\Webkul\Attribute\Contracts\AttributeOption::class, \Webkul\SAASCustomizer\Models\Attribute\AttributeOption::class);
        $this->app->concord->registerModel(\Webkul\Attribute\Contracts\AttributeOptionTranslation::class, \Webkul\SAASCustomizer\Models\Attribute\AttributeOptionTranslation::class);
        $this->app->concord->registerModel(\Webkul\Attribute\Contracts\AttributeTranslation::class, \Webkul\SAASCustomizer\Models\Attribute\AttributeTranslation::class);

        // Category Models Starts
        $this->app->concord->registerModel(\Webkul\Category\Contracts\Category::class, \Webkul\SAASCustomizer\Models\Category\Category::class);
        $this->app->concord->registerModel(\Webkul\Category\Contracts\CategoryTranslation::class, \Webkul\SAASCustomizer\Models\Category\CategoryTranslation::class);

        // Checkout Models Starts
        $this->app->concord->registerModel(\Webkul\Checkout\Contracts\Cart::class, \Webkul\SAASCustomizer\Models\Checkout\Cart::class);
        $this->app->concord->registerModel(\Webkul\Checkout\Contracts\CartAddress::class, \Webkul\SAASCustomizer\Models\Checkout\CartAddress::class);
        $this->app->concord->registerModel(\Webkul\Checkout\Contracts\CartItem::class, \Webkul\SAASCustomizer\Models\Checkout\CartItem::class);
        $this->app->concord->registerModel(\Webkul\Checkout\Contracts\CartPayment::class, \Webkul\SAASCustomizer\Models\Checkout\CartPayment::class);
        $this->app->concord->registerModel(\Webkul\Checkout\Contracts\CartShippingRate::class, \Webkul\SAASCustomizer\Models\Checkout\CartShippingRate::class);

        // Core Models Starts
        $this->app->concord->registerModel(\Webkul\Core\Contracts\Channel::class, \Webkul\SAASCustomizer\Models\Core\Channel::class);
        $this->app->concord->registerModel(\Webkul\Core\Contracts\CoreConfig::class, \Webkul\SAASCustomizer\Models\Core\CoreConfig::class);
        $this->app->concord->registerModel(\Webkul\Core\Contracts\Currency::class, \Webkul\SAASCustomizer\Models\Core\Currency::class);
        $this->app->concord->registerModel(\Webkul\Core\Contracts\CurrencyExchangeRate::class, \Webkul\SAASCustomizer\Models\Core\CurrencyExchangeRate::class);
        $this->app->concord->registerModel(\Webkul\Core\Contracts\Locale::class, \Webkul\SAASCustomizer\Models\Core\Locale::class);
        $this->app->concord->registerModel(\Webkul\Core\Contracts\Slider::class, \Webkul\SAASCustomizer\Models\Core\Slider::class);
        $this->app->concord->registerModel(\Webkul\Core\Contracts\SubscribersList::class, \Webkul\SAASCustomizer\Models\Core\SubscribersList::class);

        // Customer Models Starts
        $this->app->concord->registerModel(\Webkul\Customer\Contracts\Customer::class, \Webkul\SAASCustomizer\Models\Customer\Customer::class);
        $this->app->concord->registerModel(\Webkul\Customer\Contracts\CustomerAddress::class, \Webkul\SAASCustomizer\Models\Customer\CustomerAddress::class);
        $this->app->concord->registerModel(\Webkul\Customer\Contracts\CustomerGroup::class, \Webkul\SAASCustomizer\Models\Customer\CustomerGroup::class);
        $this->app->concord->registerModel(\Webkul\Customer\Contracts\Wishlist::class, \Webkul\SAASCustomizer\Models\Customer\Wishlist::class);

        // Inventory Models Starts
        $this->app->concord->registerModel(\Webkul\Inventory\Contracts\InventorySource::class, \Webkul\SAASCustomizer\Models\Inventory\InventorySource::class);

        // Product Models Starts
        $this->app->concord->registerModel(\Webkul\Product\Contracts\Product::class, \Webkul\SAASCustomizer\Models\Product\Product::class);
        $this->app->concord->registerModel(\Webkul\Product\Contracts\ProductAttributeValue::class, \Webkul\SAASCustomizer\Models\Product\ProductAttributeValue::class);
        $this->app->concord->registerModel(\Webkul\Product\Contracts\ProductFlat::class, \Webkul\SAASCustomizer\Models\Product\ProductFlat::class);
        $this->app->concord->registerModel(\Webkul\Product\Contracts\ProductImage::class, \Webkul\SAASCustomizer\Models\Product\ProductImage::class);
        $this->app->concord->registerModel(\Webkul\Product\Contracts\ProductInventory::class, \Webkul\SAASCustomizer\Models\Product\ProductInventory::class);

        $this->app->concord->registerModel(\Webkul\Product\Contracts\ProductOrderedInventory::class, \Webkul\SAASCustomizer\Models\Product\ProductOrderedInventory::class);
        $this->app->concord->registerModel(\Webkul\Product\Contracts\ProductReview::class, \Webkul\SAASCustomizer\Models\Product\ProductReview::class);

        // Sales Models Starts
        $this->app->concord->registerModel(\Webkul\Sales\Contracts\Invoice::class, \Webkul\SAASCustomizer\Models\Sales\Invoice::class);
        $this->app->concord->registerModel(\Webkul\Sales\Contracts\InvoiceItem::class, \Webkul\SAASCustomizer\Models\Sales\InvoiceItem::class);
        $this->app->concord->registerModel(\Webkul\Sales\Contracts\Order::class, \Webkul\SAASCustomizer\Models\Sales\Order::class);
        $this->app->concord->registerModel(\Webkul\Sales\Contracts\OrderAddress::class, \Webkul\SAASCustomizer\Models\Sales\OrderAddress::class);
        $this->app->concord->registerModel(\Webkul\Sales\Contracts\OrderItem::class, \Webkul\SAASCustomizer\Models\Sales\OrderItem::class);
        $this->app->concord->registerModel(\Webkul\Sales\Contracts\OrderPayment::class, \Webkul\SAASCustomizer\Models\Sales\OrderPayment::class);
        $this->app->concord->registerModel(\Webkul\Sales\Contracts\Shipment::class, \Webkul\SAASCustomizer\Models\Sales\Shipment::class);
        // $this->app->concord->registerModel(\Webkul\Sales\Contracts\ShipmentItem::class, \Webkul\SAASCustomizer\Models\Sales\ShipmentItem::class);

        // Tax Models Starts
        $this->app->concord->registerModel(\Webkul\Tax\Contracts\TaxCategory::class, \Webkul\SAASCustomizer\Models\Tax\TaxCategory::class);
        $this->app->concord->registerModel(\Webkul\Tax\Contracts\TaxMap::class, \Webkul\SAASCustomizer\Models\Tax\TaxMap::class);
        $this->app->concord->registerModel(\Webkul\Tax\Contracts\TaxRate::class, \Webkul\SAASCustomizer\Models\Tax\TaxRate::class);

        // User Models Starts
        $this->app->concord->registerModel(\Webkul\User\Contracts\Admin::class, \Webkul\SAASCustomizer\Models\User\Admin::class);
        $this->app->concord->registerModel(\Webkul\User\Contracts\Role::class, \Webkul\SAASCustomizer\Models\User\Role::class);

        //Discount Model
        $this->app->concord->registerModel(\Webkul\Discount\Contracts\CartRule::class, \Webkul\SAASCustomizer\Models\Discount\CartRule::class);
        $this->app->concord->registerModel(\Webkul\Discount\Contracts\CatalogRule::class, \Webkul\SAASCustomizer\Models\Discount\CatalogRule::class);
        $this->app->concord->registerModel(\Webkul\Discount\Contracts\CatalogRuleProducts::class, \Webkul\SAASCustomizer\Models\Discount\CatalogRuleProducts::class);
        $this->app->concord->registerModel(\Webkul\Discount\Contracts\CatalogRuleProductsPrice::class, \Webkul\SAASCustomizer\Models\Discount\CatalogRuleProductsPrice::class);
    }

    /**
     * Boot all the model observers
     */
    public function bootModelObservers()
    {
        \Webkul\SAASCustomizer\Models\Attribute\Attribute::observe(\Webkul\SAASCustomizer\Observers\Attribute\AttributeObserver::class);

        \Webkul\SAASCustomizer\Models\Attribute\AttributeFamily::observe(\Webkul\SAASCustomizer\Observers\Attribute\AttributeFamilyObserver::class);

        \Webkul\SAASCustomizer\Models\Attribute\AttributeGroup::observe(\Webkul\SAASCustomizer\Observers\Attribute\AttributeGroupObserver::class);

        \Webkul\SAASCustomizer\Models\Attribute\AttributeOption::observe(\Webkul\SAASCustomizer\Observers\Attribute\AttributeOptionObserver::class);

        \Webkul\SAASCustomizer\Models\Attribute\AttributeOptionTranslation::observe(\Webkul\SAASCustomizer\Observers\Attribute\AttributeOptionTranslationObserver::class);

        \Webkul\SAASCustomizer\Models\Attribute\AttributeTranslation::observe(\Webkul\SAASCustomizer\Observers\Attribute\AttributeTranslationObserver::class);

        \Webkul\SAASCustomizer\Models\Category\Category::observe(\Webkul\SAASCustomizer\Observers\Category\CategoryObserver::class);

        \Webkul\SAASCustomizer\Models\Category\CategoryTranslation::observe(\Webkul\SAASCustomizer\Observers\Category\CategoryTranslationObserver::class);

        \Webkul\SAASCustomizer\Models\Checkout\Cart::observe(\Webkul\SAASCustomizer\Observers\Checkout\CartObserver::class);

        \Webkul\SAASCustomizer\Models\Checkout\CartAddress::observe(\Webkul\SAASCustomizer\Observers\Checkout\CartAddressObserver::class);

        \Webkul\SAASCustomizer\Models\Checkout\CartItem::observe(\Webkul\SAASCustomizer\Observers\Checkout\CartItemObserver::class);

        \Webkul\SAASCustomizer\Models\Checkout\CartPayment::observe(\Webkul\SAASCustomizer\Observers\Checkout\CartPaymentObserver::class);

        \Webkul\SAASCustomizer\Models\Checkout\CartShippingRate::observe(\Webkul\SAASCustomizer\Observers\Checkout\CartShippingRateObserver::class);

        \Webkul\SAASCustomizer\Models\Core\Channel::observe(\Webkul\SAASCustomizer\Observers\Core\ChannelObserver::class);

        \Webkul\SAASCustomizer\Models\Core\CoreConfig::observe(\Webkul\SAASCustomizer\Observers\Core\CoreConfigObserver::class);

        \Webkul\SAASCustomizer\Models\Core\Currency::observe(\Webkul\SAASCustomizer\Observers\Core\CurrencyObserver::class);

        \Webkul\SAASCustomizer\Models\Core\CurrencyExchangeRate::observe(\Webkul\SAASCustomizer\Observers\Core\CurrencyExchangeRateObserver::class);

        \Webkul\SAASCustomizer\Models\Core\Locale::observe(\Webkul\SAASCustomizer\Observers\Core\LocaleObserver::class);

        \Webkul\SAASCustomizer\Models\Core\Slider::observe(\Webkul\SAASCustomizer\Observers\Core\SliderObserver::class);

        \Webkul\SAASCustomizer\Models\Core\SubscribersList::observe(\Webkul\SAASCustomizer\Observers\Core\SubscribersListObserver::class);

        \Webkul\SAASCustomizer\Models\Customer\Customer::observe(\Webkul\SAASCustomizer\Observers\Customer\CustomerObserver::class);

        \Webkul\SAASCustomizer\Models\Customer\CustomerAddress::observe(\Webkul\SAASCustomizer\Observers\Customer\CustomerAddressObserver::class);

        \Webkul\SAASCustomizer\Models\Customer\CustomerGroup::observe(\Webkul\SAASCustomizer\Observers\Customer\CustomerGroupObserver::class);

        \Webkul\SAASCustomizer\Models\Customer\Wishlist::observe(\Webkul\SAASCustomizer\Observers\Customer\WishlistObserver::class);

        \Webkul\SAASCustomizer\Models\Inventory\InventorySource::observe(\Webkul\SAASCustomizer\Observers\Inventory\InventorySourceObserver::class);

        \Webkul\SAASCustomizer\Models\Product\Product::observe(\Webkul\SAASCustomizer\Observers\Product\ProductObserver::class);

        \Webkul\SAASCustomizer\Models\Product\ProductAttributeValue::observe(\Webkul\SAASCustomizer\Observers\Product\ProductAttributeValueObserver::class);

        \Webkul\SAASCustomizer\Models\Product\ProductFlat::observe(\Webkul\SAASCustomizer\Observers\Product\ProductFlatObserver::class);

        \Webkul\SAASCustomizer\Models\Product\ProductImage::observe(\Webkul\SAASCustomizer\Observers\Product\ProductImageObserver::class);

        \Webkul\SAASCustomizer\Models\Product\ProductInventory::observe(\Webkul\SAASCustomizer\Observers\Product\ProductInventoryObserver::class);

        \Webkul\SAASCustomizer\Models\Product\ProductOrderedInventory::observe(\Webkul\SAASCustomizer\Observers\Product\ProductOrderedInventoryObserver::class);

        \Webkul\SAASCustomizer\Models\Product\ProductReview::observe(\Webkul\SAASCustomizer\Observers\Product\ProductReviewObserver::class);

        \Webkul\SAASCustomizer\Models\Sales\Invoice::observe(\Webkul\SAASCustomizer\Observers\Sales\InvoiceObserver::class);

        \Webkul\SAASCustomizer\Models\Sales\InvoiceItem::observe(\Webkul\SAASCustomizer\Observers\Sales\InvoiceItemObserver::class);

        \Webkul\SAASCustomizer\Models\Sales\Order::observe(\Webkul\SAASCustomizer\Observers\Sales\OrderObserver::class);

        \Webkul\SAASCustomizer\Models\Sales\OrderAddress::observe(\Webkul\SAASCustomizer\Observers\Sales\OrderAddressObserver::class);

        \Webkul\SAASCustomizer\Models\Sales\OrderItem::observe(\Webkul\SAASCustomizer\Observers\Sales\OrderItemObserver::class);

        \Webkul\SAASCustomizer\Models\Sales\OrderPayment::observe(\Webkul\SAASCustomizer\Observers\Sales\OrderPaymentObserver::class);

        \Webkul\SAASCustomizer\Models\Sales\Shipment::observe(\Webkul\SAASCustomizer\Observers\Sales\ShipmentObserver::class);

        \Webkul\SAASCustomizer\Models\Sales\ShipmentItem::observe(\Webkul\SAASCustomizer\Observers\Sales\ShipmentObserver::class);

        \Webkul\SAASCustomizer\Models\Tax\TaxCategory::observe(\Webkul\SAASCustomizer\Observers\Tax\TaxCategoryObserver::class);

        \Webkul\SAASCustomizer\Models\Tax\TaxRate::observe(\Webkul\SAASCustomizer\Observers\Tax\TaxRateObserver::class);

        \Webkul\SAASCustomizer\Models\User\Admin::observe(\Webkul\SAASCustomizer\Observers\User\AdminObserver::class);

        \Webkul\SAASCustomizer\Models\User\Role::observe(\Webkul\SAASCustomizer\Observers\User\RoleObserver::class);

        \Webkul\SAASCustomizer\Models\Discount\CartRule::observe(\Webkul\SAASCustomizer\Observers\Discount\CartRuleObserver::class);

        \Webkul\SAASCustomizer\Models\CMS\CMS::observe(\Webkul\SAASCustomizer\Observers\CMS\CMSObserver::class);

        \Webkul\SAASCustomizer\Models\Discount\CatalogRule::observe(\Webkul\SAASCustomizer\Observers\Discount\CatalogRuleObserver::class);

        \Webkul\SAASCustomizer\Models\Discount\CatalogRuleProducts::observe(\Webkul\SAASCustomizer\Observers\Discount\CatalogRuleProductsObserver::class);

        \Webkul\SAASCustomizer\Models\Discount\CatalogRuleProductsPrice::observe(\Webkul\SAASCustomizer\Observers\Discount\CatalogRuleProductsPriceObserver::class);
    }
}