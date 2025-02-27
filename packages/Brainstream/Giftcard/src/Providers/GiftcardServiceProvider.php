<?php

namespace Brainstream\Giftcard\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Brainstream\Giftcard\Cart;
use Webkul\Checkout\Cart as BaseCart;
use Webkul\Sales\Repositories\InvoiceRepository;
use Brainstream\Giftcard\Repositories\CustomInvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Brainstream\Giftcard\Repositories\CustomOrderRepository;
use Webkul\Shop\Http\Resources\CartResource;
use Brainstream\Giftcard\Http\Resources\CustomCartResource;

class GiftcardServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'giftcard');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'giftcard');

        $this->app['view']->prependNamespace('admin', __DIR__.'/../Resources/views');
        $this->app['view']->prependNamespace('shop', __DIR__.'/../Resources/views');

        $this->publishes([
            __DIR__.'/../Resources/views' => resource_path('views/vendor/admin'),
            __DIR__.'/../Resources/views' => resource_path('views/vendor/shop'),
        ]);
        
        $this->publishes([
            __DIR__ . '/../Resources/assets' => public_path('vendor/giftcard/assets'),
        ], 'public');

        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('giftcard::admin.layouts.style');
        });

        Event::listen('bagisto.shop.components.layouts.header.desktop.bottom.profile_dropdown.links.before', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('giftcard::components.giftcard-link');
        });

        Event::listen('bagisto.shop.checkout.onepage.summary.coupon.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('giftcard::components.giftcard-cartsummary');
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

        // Bind interface to implementation
        $this->app->bind(
            \Brainstream\Giftcard\Contracts\GiftCardInterface::class,
            \Brainstream\Giftcard\Repositories\GiftCardRepository::class
        );

        // Override the Cart class
        $this->app->bind(BaseCart::class, Cart::class);

        // Override the InvoiceRepository
        $this->app->bind(InvoiceRepository::class, CustomInvoiceRepository::class);

        // Override the OrderRepository
        $this->app->bind(OrderRepository::class, CustomOrderRepository::class);


    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );
    }

}
