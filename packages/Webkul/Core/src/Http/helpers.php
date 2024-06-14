<?php

use Webkul\Core\Acl;
use Webkul\Core\Core;
use Webkul\Core\Menu;
use Webkul\Core\SystemConfig;

if (! function_exists('core')) {
    /**
     * Core helper.
     */
    function core(): Core
    {
        return app('core');
    }
}

if (! function_exists('menu')) {
    /**
     * Menu helper.
     */
    function menu(): Menu
    {
        return app('menu');
    }
}

if (! function_exists('acl')) {
    /**
     * Acl helper.
     */
    function acl(): Acl
    {
        return app('acl');
    }
}

if (! function_exists('system_config')) {
    /**
     * System Config helper.
     */
    function system_config(): SystemConfig
    {
        return app('system_config');
    }
}

if (! function_exists('array_permutation')) {
    function array_permutation($input)
    {
        $results = [];

        foreach ($input as $key => $values) {
            if (empty($values)) {
                continue;
            }

            if (empty($results)) {
                foreach ($values as $value) {
                    $results[] = [$key => $value];
                }
            } else {
                $append = [];

                foreach ($results as &$result) {
                    $result[$key] = array_shift($values);

                    $copy = $result;

                    foreach ($values as $item) {
                        $copy[$key] = $item;
                        $append[] = $copy;
                    }

                    array_unshift($values, $result[$key]);
                }

                $results = array_merge($results, $append);
            }
        }

        return $results;
    }
}
