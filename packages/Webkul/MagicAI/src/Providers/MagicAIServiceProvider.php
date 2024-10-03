<?php

namespace Webkul\MagicAI\Providers;

use Illuminate\Support\ServiceProvider;

class MagicAIServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        include __DIR__.'/../Http/helpers.php';
    }
}
