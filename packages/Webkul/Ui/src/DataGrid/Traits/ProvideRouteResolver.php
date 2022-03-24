<?php

namespace Webkul\Ui\DataGrid\Traits;

trait ProvideRouteResolver
{
    /**
     * Fetch current route acl. As no access to acl key, this will fetch acl by route name.
     *
     * @param  $action
     * @return array
     */
    private function fetchCurrentRouteACL($action)
    {
        return collect(config('acl'))->filter(function ($acl) use ($action) {
            return $acl['route'] === $action['route'];
        })->first();
    }

    /**
     * Fetch route name from full url, not the current one.
     *
     * @param  $action
     * @return array
     */
    private function getRouteNameFromUrl($action, $method)
    {
        return app('router')->getRoutes()
            ->match(app('request')->create(str_replace(url('/'), '', $action), $method))
            ->getName();
    }
}