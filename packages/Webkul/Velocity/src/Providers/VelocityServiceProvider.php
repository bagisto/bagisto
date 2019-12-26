<?php

namespace Webkul\Velocity\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

use Webkul\Velocity\Velocity;
use Webkul\Velocity\Facades\Velocity as VelocityFacade;

/**
 * Velocity ServiceProvider
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @author Shubham Mehrotra <shubhammehrotra.symfony@webkul.com> @shubhwebkul
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class VelocityServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        include __DIR__ . '/../Http/helpers.php';

        // TODO:- REMOVE STATIC DATA
        $products = [
            [
                'price' => 50,
                'max_price' => 25,
                'name' => 'Flower Pot Potted 1291324',
                'star-rating' => 3,
                'review-count' => 25,
                'currency-icon' => '$',
                'product-image' => app('url')->asset('themes/velocity/assets/images/product1.png'),
                'route' => "http://shubham.webkul.com/PHP/laravel/Bagisto/bagisto-velocity-theme/public/products/ertyuiop",
                'images' => [
                    app('url')->asset('themes/velocity/assets/images/product1.png'),
                    app('url')->asset('themes/velocity/assets/images/product2.png'),
                    app('url')->asset('themes/velocity/assets/images/product3.png'),
                    app('url')->asset('themes/velocity/assets/images/product4.png'),
                ]
            ], [
                'price' => 50,
                'max_price' => 25,
                'name' => 'Flower Pot Potted 1291324',
                'star-rating' => 5,
                'review-count' => 25,
                'currency-icon' => '$',
                'product-image' => app('url')->asset('themes/velocity/assets/images/product2.png'),
                'route' => "http://shubham.webkul.com/PHP/laravel/Bagisto/bagisto-velocity-theme/public/products/ertyuiop",
                'images' => [
                    app('url')->asset('themes/velocity/assets/images/product1.png'),
                    app('url')->asset('themes/velocity/assets/images/product2.png'),
                    app('url')->asset('themes/velocity/assets/images/product3.png'),
                    app('url')->asset('themes/velocity/assets/images/product4.png'),
                ]
            ], [
                'price' => 50,
                'max_price' => 25,
                'name' => 'Flower Pot Potted 1291324',
                'star-rating' => 1,
                'review-count' => 25,
                'currency-icon' => '$',
                'product-image' => app('url')->asset('themes/velocity/assets/images/product3.png'),
                'route' => "http://shubham.webkul.com/PHP/laravel/Bagisto/bagisto-velocity-theme/public/products/ertyuiop",
                'images' => [
                    app('url')->asset('themes/velocity/assets/images/product1.png'),
                    app('url')->asset('themes/velocity/assets/images/product2.png'),
                    app('url')->asset('themes/velocity/assets/images/product3.png'),
                    app('url')->asset('themes/velocity/assets/images/product4.png'),
                ]
            ], [
                'price' => 50,
                'max_price' => 25,
                'name' => 'Flower Pot Potted 1291324',
                'star-rating' => 3,
                'review-count' => 0,
                'currency-icon' => '$',
                'product-image' => app('url')->asset('themes/velocity/assets/images/product4.png'),
                'route' => "http://shubham.webkul.com/PHP/laravel/Bagisto/bagisto-velocity-theme/public/products/ertyuiop",
                'images' => [
                    app('url')->asset('themes/velocity/assets/images/product1.png'),
                    app('url')->asset('themes/velocity/assets/images/product2.png'),
                    app('url')->asset('themes/velocity/assets/images/product3.png'),
                    app('url')->asset('themes/velocity/assets/images/product4.png'),
                ]
            ], [
                'price' => 50,
                'max_price' => 25,
                'name' => 'Flower Pot Potted 1291324',
                'star-rating' => 2,
                'review-count' => 25,
                'currency-icon' => '$',
                'product-image' => app('url')->asset('themes/velocity/assets/images/product1.png'),
                'route' => "http://shubham.webkul.com/PHP/laravel/Bagisto/bagisto-velocity-theme/public/products/ertyuiop",
                'images' => [
                    app('url')->asset('themes/velocity/assets/images/product1.png'),
                    app('url')->asset('themes/velocity/assets/images/product2.png'),
                    app('url')->asset('themes/velocity/assets/images/product3.png'),
                    app('url')->asset('themes/velocity/assets/images/product4.png'),
                ]
            ], [
                'price' => 50,
                'max_price' => 25,
                'name' => 'Flower Pot Potted 1291324',
                'star-rating' => 3,
                'review-count' => 5,
                'currency-icon' => '$',
                'product-image' => app('url')->asset('themes/velocity/assets/images/product2.png'),
                'route' => "http://shubham.webkul.com/PHP/laravel/Bagisto/bagisto-velocity-theme/public/products/ertyuiop",
                'images' => [
                    app('url')->asset('themes/velocity/assets/images/product1.png'),
                    app('url')->asset('themes/velocity/assets/images/product2.png'),
                    app('url')->asset('themes/velocity/assets/images/product3.png'),
                    app('url')->asset('themes/velocity/assets/images/product4.png'),
                ]
            ],  [
                'price' => 50,
                'max_price' => 25,
                'name' => 'Flower Pot Potted 1291324',
                'star-rating' => 2,
                'review-count' => 5,
                'currency-icon' => '$',
                'product-image' => app('url')->asset('themes/velocity/assets/images/product2.png'),
                'route' => "http://shubham.webkul.com/PHP/laravel/Bagisto/bagisto-velocity-theme/public/products/ertyuiop",
                'images' => [
                    app('url')->asset('themes/velocity/assets/images/product1.png'),
                    app('url')->asset('themes/velocity/assets/images/product2.png'),
                    app('url')->asset('themes/velocity/assets/images/product3.png'),
                    app('url')->asset('themes/velocity/assets/images/product4.png'),
                ]
            ],
        ];

        view()->share('sampleProducts', $products);

        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        $this->app->register(EventServiceProvider::class);

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'velocity');

        $this->publishes([
            __DIR__ . '/../../publishable/assets/' => public_path('themes/velocity/assets'),
        ], 'public');

        $this->publishes([
            dirname(__DIR__) . '/Resources/views/admin/settings/channels/edit.blade.php' => base_path('resources/views/vendor/admin/settings/channels/edit.blade.php')
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/' => resource_path('themes/velocity/views'),
        ]);

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'velocity');
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

    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );

        // $this->mergeConfigFrom(
        //     dirname(__DIR__) . '/Config/acl.php', 'acl'
        // );
    }

    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerFacades()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('velocity', VelocityFacade::class);

        $this->app->singleton('velocity', function () {
            return app()->make(Velocity::class);
        });
    }
}
