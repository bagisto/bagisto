<?php

namespace Webkul\Core\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as BasePreventRequestsDuringMaintenance;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PreventRequestsDuringMaintenance extends BasePreventRequestsDuringMaintenance
{
    /**
     * Route names that stay reachable during maintenance.
     *
     * @var array
     */
    protected $excludedNames = [];

    /**
     * The addresses the current channel lets through during maintenance.
     *
     * @var array
     */
    protected $excludedIPs = [];

    /**
     * Hold back a request while the application is down and the request's channel is under maintenance, letting the
     * excluded paths, the channel's allowed addresses and a holder of the bypass secret through, as Laravel's does.
     *
     * @param  Request  $request
     * @return mixed
     *
     * @throws HttpException
     */
    public function handle($request, Closure $next)
    {
        if ($this->app->maintenanceMode()->active()) {
            try {
                $data = $this->app->maintenanceMode()->data();
            } catch (\ErrorException|\TypeError $exception) {
                if (! $this->app->maintenanceMode()->active()) {
                    return $next($request);
                }

                throw $exception;
            }

            if (
                is_string($data['secret'] ?? null)
                && hash_equals($data['secret'], $request->path())
            ) {
                return $this->bypassResponse($data['secret']);
            }

            if ($this->hasValidBypassCookie($request, $data)) {
                return $next($request);
            }

            $this->setAllowedIps();

            if (
                in_array($request->ip(), $this->excludedIPs)
                || $this->inExceptArray($request)
                || ! (bool) core()->getCurrentChannel()->is_maintenance_on
            ) {
                return $next($request);
            }

            if (
                $request->route() instanceof Route
                && in_array($request->route()->getName(), $this->excludedNames)
            ) {
                return $next($request);
            }

            if (
                isset($data['redirect'])
                && ! $request->expectsJson()
            ) {
                $path = $data['redirect'] === '/'
                    ? $data['redirect']
                    : trim($data['redirect'], '/');

                if ($request->path() !== $path) {
                    return redirect($path);
                }
            }

            if (
                isset($data['template'])
                && ! $request->expectsJson()
            ) {
                return response(
                    $data['template'],
                    $data['status'] ?? 503,
                    $this->getHeaders($data)
                );
            }

            throw new HttpException(
                $data['status'] ?? 503,
                'Service Unavailable',
                null,
                $this->getHeaders($data)
            );
        }

        return $next($request);
    }

    /**
     * The paths maintenance mode lets through, the admin url and every path under it among them, which `down`
     * also writes into the down file for the maintenance page rendered before the application boots.
     */
    public function getExcludedPaths(): array
    {
        $adminUrl = trim((string) config('app.admin_url'), '/');

        if ($adminUrl === '') {
            return parent::getExcludedPaths();
        }

        return array_merge(parent::getExcludedPaths(), [$adminUrl, $adminUrl.'/*']);
    }

    /**
     * Read the addresses the current channel lets through during maintenance.
     */
    protected function setAllowedIps(): void
    {
        if ($channel = core()->getCurrentChannel()) {
            $this->excludedIPs = array_map('trim', explode(',', $channel->allowed_ips ?? ''));
        }
    }
}
