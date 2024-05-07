<?php

use Webkul\Menu\Menu;

if (! function_exists('menu')) {
    /**
     * Menu helper.
     */
    function menu(): Menu
    {
        return app('menu');
    }
}
