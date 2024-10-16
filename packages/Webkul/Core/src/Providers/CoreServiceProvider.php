<?php

namespace Webkul\Core\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\Theme\ViewRenderEventManager;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        include __DIR__.'/../Http/helpers.php';

        $this->registerCommands();

        $this->registerOverrides();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'core');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'core');

        Event::listen('bagisto.shop.layout.body.after', static function (ViewRenderEventManager $viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('core::blade.tracer.style');
        });

        Event::listen('bagisto.admin.layout.head', static function (ViewRenderEventManager $viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('core::blade.tracer.style');
        });

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('invoice:cron')->dailyAt('3:00');
        });

        $this->app->register(EventServiceProvider::class);
        $this->app->register(ImageServiceProvider::class);
        $this->app->register(VisitorServiceProvider::class);
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
     * Register the overrides.
     */
    protected function registerOverrides(): void
    {
        $this->app->extend(
            \Illuminate\Foundation\Console\DownCommand::class,
            fn () => new \Webkul\Core\Console\Commands\UpCommand
        );

        $this->app->extend(
            \Illuminate\Foundation\Console\DownCommand::class,
            fn () => new \Webkul\Core\Console\Commands\DownCommand
        );

        $this->app->bind(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            \Webkul\Core\Exceptions\Handler::class
        );

        $this->app->bind(
            \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
            fn ($app) => new \Webkul\Core\Http\Middleware\PreventRequestsDuringMaintenance($app)
        );

        $this->app->singleton(
            \Elastic\Elasticsearch\Client::class,
            fn () => \Webkul\Core\Facades\ElasticSearch::getFacadeApplication()->connection()
        );

        $this->app->singleton(
            'blade.compiler',
            fn ($app) => new \Webkul\Core\View\Compilers\BladeCompiler($app['files'], $app['config']['view.compiled'])
        );
    }
}
