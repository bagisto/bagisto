<?php

use Webkul\Theme\Facades\Themes;
use Webkul\Theme\ThemeStorage;
use Webkul\Theme\ViewRenderEventManager;

if (! function_exists('themes')) {
    /**
     * Themes.
     *
     * @return Webkul\Theme\Themes
     */
    function themes()
    {
        return Themes::getFacadeRoot();
    }
}

if (! function_exists('bagisto_asset')) {
    /**
     * Bagisto asset.
     *
     * @return string
     */
    function bagisto_asset(string $path, ?string $namespace = null)
    {
        return themes()->url($path, $namespace);
    }
}

if (! function_exists('bagisto_theme_storage')) {
    /**
     * Bagisto theme storage.
     *
     * Resolves what a theme has stored, where `bagisto_asset()` resolves what it ships.
     *
     * @return ThemeStorage
     */
    function bagisto_theme_storage()
    {
        return app(ThemeStorage::class);
    }
}

if (! function_exists('view_render_event')) {
    /**
     * View render event.
     *
     * @return mixed
     */
    function view_render_event(string $eventName, mixed $params = null)
    {
        return app(ViewRenderEventManager::class)
            ->handleRenderEvent($eventName, $params)
            ->render();
    }
}
