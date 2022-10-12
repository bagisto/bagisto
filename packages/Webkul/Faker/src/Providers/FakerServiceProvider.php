<?php

namespace Webkul\Faker\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Faker\Commands\Console\Faker;

class FakerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([Faker::class]);
    }
}