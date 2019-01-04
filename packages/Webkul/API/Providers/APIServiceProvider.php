<?php

namespace Webkul\API\Providers;

use Illuminate\Support\ServiceProvider;

class APIServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../Http/api.php');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $app['Dingo\Api\Auth\Auth']->extend('oauth', function ($app) {
        //     return new Dingo\Api\Auth\Provider\JWT($app['Tymon\JWTAuth\JWTAuth']);
        // });

        // $app['Dingo\Api\Http\RateLimit\Handler']->extend(function ($app) {
        //     return new Dingo\Api\Http\RateLimit\Throttle\Authenticated;
        // });

        // $app['Dingo\Api\Transformer\Factory']->setAdapter(function ($app) {
        //     $fractal = new League\Fractal\Manager;

        //     $fractal->setSerializer(new League\Fractal\Serializer\JsonApiSerializer);

        //     return new Dingo\Api\Transformer\Adapter\Fractal($fractal);
        // });
    }
}
