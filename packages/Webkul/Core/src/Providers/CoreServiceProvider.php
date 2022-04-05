<?php

namespace Webkul\Core\Providers;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Webkul\Core\Core;
use Webkul\Core\Exceptions\Handler;
use Webkul\Core\Facades\Core as CoreFacade;
use Webkul\Core\Models\SliderProxy;
use Webkul\Core\Observers\SliderObserver;
use Webkul\Core\View\Compilers\BladeCompiler;
use Webkul\Theme\ViewRenderEventManager;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        include __DIR__ . '/../Http/helpers.php';

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'core');

        Validator::extend('slug', 'Webkul\Core\Contracts\Validations\Slug@passes');

        Validator::extend('code', 'Webkul\Core\Contracts\Validations\Code@passes');

        Validator::extend('decimal', 'Webkul\Core\Contracts\Validations\Decimal@passes');

        $this->publishes([
            dirname(__DIR__) . '/Config/concord.php' => config_path('concord.php'),
            dirname(__DIR__) . '/Config/sanctum.php' => config_path('sanctum.php'),
            dirname(__DIR__) . '/Config/scout.php'   => config_path('scout.php'),
        ]);

        $this->app->bind(ExceptionHandler::class, Handler::class);

        SliderProxy::observe(SliderObserver::class);

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'core');

        Event::listen('bagisto.shop.layout.body.after', static function (ViewRenderEventManager $viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('core::blade.tracer.style');
        });

        Event::listen('bagisto.admin.layout.head', static function (ViewRenderEventManager $viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('core::blade.tracer.style');
        });

        $this->app->extend('command.down', function () {
            return new \Webkul\Core\Console\Commands\DownCommand;
        });

        $this->app->extend('command.up', function () {
            return new \Webkul\Core\Console\Commands\UpCommand;
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerFacades();

        $this->registerCommands();

        $this->registerBladeCompiler();
    }

    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerFacades(): void
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('core', CoreFacade::class);

        $this->app->singleton('core', function () {
            return app()->make(Core::class);
        });
    }

    /**
     * Register the console commands of this package.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Webkul\Core\Console\Commands\BagistoPublish::class,
                \Webkul\Core\Console\Commands\BagistoVersion::class,
                \Webkul\Core\Console\Commands\Install::class,
                \Webkul\Core\Console\Commands\ExchangeRateUpdate::class,
                \Webkul\Core\Console\Commands\BookingCron::class,
                \Webkul\Core\Console\Commands\InvoiceOverdueCron::class,
            ]);
        }

        $this->commands([
            \Webkul\Core\Console\Commands\DownChannelCommand::class,
            \Webkul\Core\Console\Commands\UpChannelCommand::class,
        ]);
    }

    /**
     * Register the Blade compiler implementation.
     *
     * @return void
     */
    public function registerBladeCompiler(): void
    {
        $this->app->singleton('blade.compiler', function ($app) {
            return new BladeCompiler($app['files'], $app['config']['view.compiled']);
        });
    }
}
