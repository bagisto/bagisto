<?php

namespace Webkul\AdminAuthCheck\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // 'Illuminate\Auth\Events\Attempting' => [
        //     'Webkul\AdminAuthCheck\Listeners\LoginAuthenticationAttempt'
        // ], // make use of this to stop admins logging into other sites

        'Illuminate\Auth\Events\Authenticated' => [
            'Webkul\AdminAuthCheck\Listeners\LoginAuthenticationAttempt'
        ]
    ];
}