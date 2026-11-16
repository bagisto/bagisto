<?php

namespace Webkul\Admin\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Queue\Events\JobPopping;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webkul\Admin\CommandPalette\CommandPalette;
use Webkul\Admin\CommandPalette\Providers\ActionProvider;
use Webkul\Admin\CommandPalette\Providers\ConfigurationProvider;
use Webkul\Admin\CommandPalette\Providers\NavigationProvider;
use Webkul\Admin\SystemHealth\Checks\QueueWorkerCheck;
use Webkul\Admin\SystemHealth\Checks\SchedulerCheck;
use Webkul\Admin\SystemHealth\Heartbeat;
use Webkul\Core\Http\Middleware\PreventRequestsDuringMaintenance;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();

        $this->registerCommandPalette();

        $this->app->singleton(Heartbeat::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Route::middleware(['web', PreventRequestsDuringMaintenance::class])->group(__DIR__.'/../Routes/web.php');

        $this->registerBroadcasting();

        $this->registerHeartbeats();

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'admin');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'admin');

        Blade::anonymousComponentPath(__DIR__.'/../Resources/views/components', 'admin');

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register package config.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/menu.php',
            'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/acl.php',
            'acl'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/system.php',
            'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/command-palette.php',
            'command_palette'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/notification-groups.php',
            'notification_groups'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/notification-types.php',
            'notification_types'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/system-health.php',
            'system_health'
        );
    }

    /**
     * Register what the command palette searches over.
     */
    protected function registerCommandPalette(): void
    {
        $this->app->singleton(CommandPalette::class);

        $this->callAfterResolving(CommandPalette::class, function (CommandPalette $palette) {
            $palette->register(NavigationProvider::class);
            $palette->register(ConfigurationProvider::class);
            $palette->register(ActionProvider::class);
        });
    }

    /**
     * Register the broadcast authorization route and the channels the panel listens on.
     */
    protected function registerBroadcasting(): void
    {
        Broadcast::routes();

        require __DIR__.'/../Routes/channels.php';
    }

    /**
     * Have the queue worker and the scheduler record that they are alive. A `queue:listen` worker
     * never loops, so the worker also beats each time it asks the queue for a job.
     */
    protected function registerHeartbeats(): void
    {
        $beat = fn () => $this->app->make(Heartbeat::class)->beat(QueueWorkerCheck::HEARTBEAT);

        Queue::looping($beat);

        Event::listen(JobPopping::class, $beat);

        Queue::before($beat);

        Queue::after($beat);

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->call(fn () => $this->app->make(Heartbeat::class)->beat(SchedulerCheck::HEARTBEAT))
                ->everyMinute()
                ->name('admin:system-health:scheduler-heartbeat');
        });
    }
}
