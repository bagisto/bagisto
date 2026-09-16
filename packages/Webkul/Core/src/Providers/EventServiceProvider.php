<?php

namespace Webkul\Core\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Webkul\Core\Listeners\CleanCacheRepository;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Prettus\Repository\Events\RepositoryEntityCreated' => [
            CleanCacheRepository::class,
        ],

        'Prettus\Repository\Events\RepositoryEntityUpdated' => [
            CleanCacheRepository::class,
        ],

        'Prettus\Repository\Events\RepositoryEntityDeleted' => [
            CleanCacheRepository::class,
        ],
    ];
}
