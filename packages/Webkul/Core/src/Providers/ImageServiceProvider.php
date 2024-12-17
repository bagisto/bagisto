<?php

namespace Webkul\Core\Providers;

use Intervention\Image\ImageManager;
use Intervention\Image\ImageServiceProvider as BaseImageServiceProvider;

/**
 * This is the overridden `ImageServiceProvider` class from the `intervention/image` package. The base class
 * supports all versions of Laravel, but this class only supports the current Laravel version used by Bagisto.
 */
class ImageServiceProvider extends BaseImageServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('image', function ($app) {
            return new ImageManager($this->getImageConfig($app));
        });

        $this->app->alias('image', 'Intervention\Image\ImageManager');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->cacheIsInstalled()
            ? $this->bootstrapImageCache()
            : null;
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['image'];
    }

    /**
     * Bootstrap imagecache
     *
     * @return void
     */
    protected function bootstrapImageCache()
    {
        /**
         * Image cache route.
         */
        if (is_string(config('imagecache.route'))) {
            $filenamePattern = '[ \w\\.\\/\\-\\@\(\)\=]+';

            $this->app['router']->get(config('imagecache.route').'/{template}/{filename}', [
                'uses' => 'Webkul\Core\ImageCache\Controller@getResponse',
                'as'   => 'imagecache',
            ])->where(['filename' => $filenamePattern]);
        }
    }

    /**
     * Determines if Intervention Image Cache is installed.
     *
     * @return bool
     */
    private function cacheIsInstalled()
    {
        return class_exists('Intervention\\Image\\ImageCache');
    }

    /**
     * Return image configuration as array.
     *
     * @param  Application  $app
     * @return array
     */
    private function getImageConfig($app)
    {
        $config = $app['config']->get('image');

        if (is_null($config)) {
            return [];
        }

        return $config;
    }
}
