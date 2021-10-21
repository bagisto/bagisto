<?php

namespace Webkul\Core\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as Original;
use Illuminate\Routing\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckForMaintenanceMode extends Original
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Current channel.
     *
     * @var \Webkul\Core\Models\Channel
     */
    protected $channel;

    /**
     * Exclude route names.
     */
    protected $excludedNames = [];

    /**
     * Exclude route uris.
     */
    protected $except = [];

    /**
     * Exclude IPs.
     */
    protected $excludedIPs = [];

    /**
     * Constructor.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     */
    public function __construct(Application $app)
    {
        /* application */
        $this->app = $app;

        /* current channel */
        $this->channel = core()->getCurrentChannel();

        /* adding exception for admin routes */
        $this->except[] = env('APP_ADMIN_URL', 'admin') . '*';

        /* exclude ips */
        $this->setAllowedIps();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next)
    {
        if ($this->app->isDownForMaintenance()) {
            $response = $next($request);

            if (in_array($request->ip(), $this->excludedIPs)) {
                return $response;
            }

            $route = $request->route();

            if ($route instanceof Route) {
                if (in_array($route->getName(), $this->excludedNames)) {
                    return $response;
                }
            }

            if ($this->shouldPassThrough($request)) {
                return $response;
            }

            if (! (bool) $this->channel->is_maintenance_on) {
                return $response;
            }

            throw new HttpException(503);
        }

        return $next($request);
    }

    /**
     * Set allowed IPs.
     *
     * @return void
     */
    protected function setAllowedIps()
    {
        if ($this->channel) {
            $this->excludedIPs = array_map('trim', explode(',', $this->channel->allowed_ips));
        }
    }

    /**
     * Check for the except routes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return boolean
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
