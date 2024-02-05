<?php

namespace Webkul\Core\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as BaseCheckForMaintenanceMode;
use Illuminate\Routing\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Webkul\Installer\Helpers\DatabaseManager;

class CheckForMaintenanceMode extends BaseCheckForMaintenanceMode
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Exclude route names.
     *
     * @var array
     */
    protected $excludedNames = [];

    /**
     * Exclude Channel Ip's.
     *
     * @var array
     */
    protected $excludedIPs = [];

    /**
     * Exclude route uris.
     *
     * @var array
     */
    protected $except = [];

    /**
     * Constructor.
     */
    public function __construct(
        protected DatabaseManager $databaseManager,
        Application $app
    ) {
        /* application */
        $this->app = $app;

        /* adding exception for admin routes */
        $this->except[] = config('app.admin_url').'*';

        if ($this->databaseManager->isInstalled()) {
            /* exclude ips */
            $this->setAllowedIps();
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next)
    {
        if ($this->databaseManager->isInstalled() && $this->app->isDownForMaintenance()) {
            $response = $next($request);

            if (
                in_array($request->ip(), $this->excludedIPs)
                || $this->shouldPassThrough($request)
                || ! (bool) core()->getCurrentChannel()->is_maintenance_on
            ) {
                return $response;
            }

            $route = $request->route();

            if ($route instanceof Route) {
                if (in_array($route->getName(), $this->excludedNames)) {
                    return $response;
                }
            }

            throw new HttpException(503);
        }

        return $next($request);
    }

    /**
     * Set allowed IPs.
     */
    protected function setAllowedIps(): void
    {
        if ($channel = core()->getCurrentChannel()) {
            $this->excludedIPs = array_map('trim', explode(',', $channel->allowed_ips ?? ''));
        }
    }

    /**
     * Check for the except routes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function shouldPassThrough($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
