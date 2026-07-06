<?php

namespace Webkul\Core\Providers;

use Illuminate\Queue\Events\JobQueued;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\Events\Looping;
use Webkul\Theme\ViewRenderEventManager;
use Elastic\Elasticsearch\Client;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Console\DownCommand;
use Illuminate\Foundation\Console\UpCommand;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance;
use Illuminate\Support\ServiceProvider;
use Webkul\Core\Console\Commands\BagistoVersion;
use Webkul\Core\Console\Commands\ExchangeRateUpdate;
use Webkul\Core\Console\Commands\InvoiceOverdueCron;
use Webkul\Core\Console\Commands\TranslationsChecker;
use Webkul\Core\Exceptions\Handler;
use Webkul\Core\Facades\ElasticSearch;
use Webkul\Core\View\Compilers\BladeCompiler;

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

        // Inject the Queue Worker Warning Banner into the Admin Layout
        Event::listen('bagisto.admin.layout.content.before', static function (ViewRenderEventManager $viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('admin::components.layouts.queue-worker-warning');
        });

        // Register the Queue Worker Heartbeat
        if (! $this->app->runningUnitTests()) {
            Queue::looping(function (Looping $event) {
                Cache::put('queue_worker_heartbeat', now()->timestamp, now()->addMinutes(5));
            });
        }

        // Synchronous Email Alert for Dead Queue Worker
        Event::listen(JobQueued::class, static function (JobQueued $event) {
            // Do nothing if using sync queue or running tests
            if (config('queue.default') === 'sync' || app()->runningUnitTests()) {
                return;
            }

            $isWorkerDead = ! Cache::has('queue_worker_heartbeat') || Cache::get('queue_worker_heartbeat') < now()->subMinutes(5)->timestamp;
            $inCooldown = Cache::has('queue_alert_cooldown');

            // If the worker is dead and we haven't sent an email in the last 30 minutes
            if ($isWorkerDead && ! $inCooldown) {
                try {
                    // Fallback to the system's default sending address for the admin alert
                    $adminEmail = config('mail.from.address'); 
                    
                    Mail::raw("CRITICAL: The Bagisto queue worker is not running. A job was just dispatched but there is no active worker to process it. Please run 'php artisan queue:work' on your server.", function ($message) use ($adminEmail) {
                        $message->to($adminEmail)->subject('⚠️ ALERT: Bagisto Queue Worker Down');
                    });

                    // Lock alerts for 30 minutes to prevent email spam
                    Cache::put('queue_alert_cooldown', true, now()->addMinutes(30));
                } catch (\Exception $e) {
                    // Silently fail to prevent crashing the user's current request (like a checkout)
                }
            }
        });

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('invoice:cron')->dailyAt('3:00');

            $this->registerExchangeRateSchedule($schedule);
        });

        $this->app->register(EventServiceProvider::class);
        $this->app->register(DynamicSmtpServiceProvider::class);
    }

    /**
     * Register the console commands of this package.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                BagistoVersion::class,
                ExchangeRateUpdate::class,
                InvoiceOverdueCron::class,
                TranslationsChecker::class,
            ]);
        }
    }

    /**
     * Register the exchange rate update schedule based on core configuration.
     */
    protected function registerExchangeRateSchedule(Schedule $schedule): void
    {
        try {
            if (! core()->getConfigData('general.exchange_rates.schedule.enabled')) {
                return;
            }

            $frequency = core()->getConfigData('general.exchange_rates.schedule.frequency') ?: 'daily';

            $time = core()->getConfigData('general.exchange_rates.schedule.time') ?: '00:00';

            $command = $schedule->command('exchange-rate:update');

            match ($frequency) {
                'weekly' => $command->weeklyOn(1, $time),
                'monthly' => $command->monthlyOn(1, $time),
                default => $command->dailyAt($time),
            };
        } catch (\Exception) {
            // Silently skip when database is not yet available (e.g., during installation).
        }
    }

    /**
     * Register the overrides.
     */
    protected function registerOverrides(): void
    {
        $this->app->extend(
            UpCommand::class,
            fn () => new \Webkul\Core\Console\Commands\UpCommand
        );

        $this->app->extend(
            DownCommand::class,
            fn () => new \Webkul\Core\Console\Commands\DownCommand
        );

        $this->app->bind(
            ExceptionHandler::class,
            Handler::class
        );

        $this->app->bind(
            PreventRequestsDuringMaintenance::class,
            fn ($app) => new \Webkul\Core\Http\Middleware\PreventRequestsDuringMaintenance($app)
        );

        $this->app->singleton(
            Client::class,
            fn () => ElasticSearch::getFacadeApplication()->connection()
        );

        $this->app->singleton(
            'blade.compiler',
            fn ($app) => new BladeCompiler($app['files'], $app['config']['view.compiled'])
        );
    }
}
