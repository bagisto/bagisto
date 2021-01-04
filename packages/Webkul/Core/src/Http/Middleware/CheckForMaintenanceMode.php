<?php

namespace Webkul\Core\Http\Middleware;

use Closure;
use Illuminate\Routing\Route;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as Original;

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
     * Exclude ips.
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

        /* adding exception for ips */
        $this->excludedIPs = array_map('trim', explode(',', $this->channel->allowed_ips));
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

            if ($this->shouldPassThrough($request)) 
            {
                return $response;
            }

            throw new HttpException(503);
        }

        return $next($request);
    }
}