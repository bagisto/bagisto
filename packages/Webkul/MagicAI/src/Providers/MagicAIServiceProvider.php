<?php

namespace Webkul\MagicAI\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\MagicAI\MagicAI;

class MagicAIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/magic_ai.php',
            'magic_ai'
        );

        $this->app->singleton(MagicAI::class, function (): MagicAI {
            return new MagicAI;
        });

        include __DIR__.'/../Http/helpers.php';
    }
}
