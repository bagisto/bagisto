<?php

namespace Webkul\Core\Providers;

use Elastic\Elasticsearch\Client as ElasticSearchClient;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Console\DownCommand;
use Illuminate\Foundation\Console\UpCommand;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as BasePreventRequestsDuringMaintenance;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\Core\Acl;
use Webkul\Core\Core;
use Webkul\Core\ElasticSearch;
use Webkul\Core\Facades\Acl as AclFacade;
use Webkul\Core\Facades\Core as CoreFacade;
use Webkul\Core\Facades\ElasticSearch as ElasticSearchFacade;
use Webkul\Core\Facades\Menu as MenuFacade;
use Webkul\Core\Facades\SystemConfig as SystemConfigFacade;
use Webkul\Core\Http\Middleware\PreventRequestsDuringMaintenance;
use Webkul\Core\Menu;
use Webkul\Core\SystemConfig;
use Webkul\Core\View\Compilers\BladeCompiler;
use Webkul\Theme\ViewRenderEventManager;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        include __DIR__.'/../Http/helpers.php';

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'core');

        $this->app->bind(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            \Webkul\Core\Exceptions\Handler::class
        );

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'core');

        $this->app->register(EventServiceProvider::class);

        $this->app->register(ImageServiceProvider::class);

        $this->app->register(VisitorServiceProvider::class);

        Event::listen('bagisto.shop.layout.body.after', static function (ViewRenderEventManager $viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('core::blade.tracer.style');
        });

        Event::listen('bagisto.admin.layout.head', static function (ViewRenderEventManager $viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('core::blade.tracer.style');
        });
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerFacades();

        $this->registerCommands();

        $this->registerBladeCompiler();

        $this->registerOverrides();
    }

    /**
     * Register Bouncer as a singleton.
     */
    protected function registerFacades(): void
    {
        $loader = AliasLoader::getInstance();

        $loader->alias('core', CoreFacade::class);

        $loader->alias('menu', MenuFacade::class);

        $loader->alias('acl', AclFacade::class);

        $loader->alias('system_config', SystemConfigFacade::class);

        $this->app->singleton('core', function () {
            return app()->make(Core::class);
        });

        $this->app->singleton('menu', function () {
            return app()->make(Menu::class);
        });

        $this->app->singleton('acl', function () {
            return app()->make(Acl::class);
        });

        $this->app->singleton('system_config', function () {
            return app()->make(SystemConfig::class);
        });

        /**
         * Register ElasticSearch as a singleton.
         */
        $this->app->singleton('elasticsearch', function () {
            return new ElasticSearch;
        });

        $loader->alias('elasticsearch', ElasticSearchFacade::class);

        $this->app->singleton(ElasticSearchClient::class, function () {
            return app()->make('elasticsearch')->connection();
        });
    }

    /**
     * Register the console commands of this package.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Webkul\Core\Console\Commands\BagistoVersion::class,
                \Webkul\Core\Console\Commands\ExchangeRateUpdate::class,
                \Webkul\Core\Console\Commands\InvoiceOverdueCron::class,
            ]);
        }
    }

    /**
     * Register the Blade compiler implementation.
     */
    public function registerBladeCompiler(): void
    {
        $this->app->singleton('blade.compiler', function ($app) {
            return new BladeCompiler($app['files'], $app['config']['view.compiled']);
        });
    }

    /**
     * Register the overrides.
     */
    protected function registerOverrides(): void
    {
        $this->app->extend(UpCommand::class, fn () => new \Webkul\Core\Console\Commands\UpCommand);

        $this->app->extend(DownCommand::class, fn () => new \Webkul\Core\Console\Commands\DownCommand);

        $this->app->bind(BasePreventRequestsDuringMaintenance::class, fn ($app) => new PreventRequestsDuringMaintenance($app));
    }
}
