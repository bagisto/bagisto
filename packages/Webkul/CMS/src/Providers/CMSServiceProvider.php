<?php

namespace Webkul\CMS\Providers;

use Illuminate\Support\ServiceProvider;

class CMSServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
