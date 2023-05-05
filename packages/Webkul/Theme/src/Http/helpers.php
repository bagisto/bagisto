<?php

use Webkul\Theme\ViewRenderEventManager;

if (! function_exists('themes')) {
    /**
     * Themes.
     *
     * @return \Webkul\Theme\Themes
     */
    function themes()
    {
        return app()->make('themes');
    }
}

if (! function_exists('bagisto_asset')) {
    /**
     * Bagisto asset.
     *
     * @param  string  $path
     * @param  string  $secure
     * @return string
     */
    function bagisto_asset($path, $secure = null)
    {
        return themes()->url($path, $secure);
    }
}

if (! function_exists('view_render_event')) {
    /**
     * View render event.
     *
     * @param  string  $eventName
     * @param  mixed  $params
     * @return mixed
     */
    function view_render_event($eventName, $params = null)
    {
        app()->singleton(ViewRenderEventManager::class);

        $viewEventManager = app()->make(ViewRenderEventManager::class);

        $viewEventManager->handleRenderEvent($eventName, $params);

        return $viewEventManager->render();
    }
}
