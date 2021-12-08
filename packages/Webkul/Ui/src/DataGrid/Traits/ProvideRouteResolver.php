<?php

namespace Webkul\Ui\DataGrid\Traits;

trait ProvideRouteResolver
{
	/**
	 * Fetch current route acl. As no access to acl key, this will fetch acl by route name.
	 *
	 * @param array $action
	 * @return array|null
	 */
    private function fetchCurrentRouteACL(array $action): ?array
	{
        return collect(config('acl'))->filter(fn($acl) => $acl['route'] === $action['route'])->first();
    }

	/**
	 * Fetch route name from full url, not the current one.
	 *
	 * @param  $action
	 * @param  $method
	 * @return null|string
	 */
    private function getRouteNameFromUrl($action, $method): ?string
	{
        return app('router')->getRoutes()
            ->match(app('request')->create(str_replace(url('/'), '', $action), $method))
            ->getName();
    }
}
