<?php

use Webkul\Admin\SystemHealth\Checks\ApplicationUrlCheck;
use Webkul\Admin\SystemHealth\Checks\BroadcastingCheck;
use Webkul\Admin\SystemHealth\Checks\DebugModeCheck;
use Webkul\Admin\SystemHealth\Checks\ExchangeRatesCheck;
use Webkul\Admin\SystemHealth\Checks\FailedJobsCheck;
use Webkul\Admin\SystemHealth\Checks\MailConfigurationCheck;
use Webkul\Admin\SystemHealth\Checks\ProductIndexCheck;
use Webkul\Admin\SystemHealth\Checks\QueueWorkerCheck;
use Webkul\Admin\SystemHealth\Checks\RemoteStorageCheck;
use Webkul\Admin\SystemHealth\Checks\SchedulerCheck;
use Webkul\Admin\SystemHealth\Checks\SearchEngineCheck;

return [
    /*
    |--------------------------------------------------------------------------
    | Inspection Interval
    |--------------------------------------------------------------------------
    |
    | The fewest seconds between two inspections. Admin requests trigger them,
    | so a stopped queue worker or scheduler is still reported.
    |
    */

    'interval' => 300,

    /*
    |--------------------------------------------------------------------------
    | Checks
    |--------------------------------------------------------------------------
    |
    | The checks an inspection runs, keyed by the `notification_types` key
    | raised while the check fails. Each class extends
    | Webkul\Admin\SystemHealth\Checks\AbstractCheck. Packages merge their own
    | into `system_health.checks` from their service provider.
    |
    */

    'checks' => [
        'system.queue_worker_stopped' => QueueWorkerCheck::class,
        'system.failed_jobs' => FailedJobsCheck::class,
        'system.scheduler_stopped' => SchedulerCheck::class,
        'system.mail_not_configured' => MailConfigurationCheck::class,
        'system.debug_mode_enabled' => DebugModeCheck::class,
        'system.app_url_mismatch' => ApplicationUrlCheck::class,
        'system.search_unreachable' => SearchEngineCheck::class,
        'system.storage_unreachable' => RemoteStorageCheck::class,
        'system.broadcast_unreachable' => BroadcastingCheck::class,
        'system.exchange_rates_stale' => ExchangeRatesCheck::class,
        'system.index_incomplete' => ProductIndexCheck::class,
    ],
];
