<?php

namespace Webkul\Core\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as BasePreventRequestsDuringMaintenance;
use Illuminate\Routing\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Webkul\Installer\Helpers\DatabaseManager;

class PreventRequestsDuringMaintenance extends BasePreventRequestsDuringMaintenance
{
    /**
     * Database manager instance.
     */
    protected DatabaseManager $databaseManager;

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
     * Constructor.
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->databaseManager = $this->app->make(DatabaseManager::class);

        $this->except[] = config('app.admin_url').'*';

        if ($this->databaseManager->isInstalled()) {
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
        if ($this->databaseManager->isInstalled() && $this->app->maintenanceMode()->active()) {
            try {
                $data = $this->app->maintenanceMode()->data();
            } catch (\ErrorException $exception) {
                if (! $this->app->maintenanceMode()->active()) {
                    return $next($request);
                }

                throw $exception;
            }

            if (isset($data['secret']) && $request->path() === $data['secret']) {
                return $this->bypassResponse($data['secret']);
            }

            if ($this->hasValidBypassCookie($request, $data)) {
                return $next($request);
            }

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

            if (isset($data['redirect'])) {
                $path = $data['redirect'] === '/'
                    ? $data['redirect']
                    : trim($data['redirect'], '/');

                if ($request->path() !== $path) {
                    return redirect($path);
                }
            }

            if (isset($data['template'])) {
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
     * Set allowed IPs.
     */
    protected function setAllowedIps(): void
    {
        if ($channel = core()->getCurrentChannel()) {
            $this->excludedIPs = array_map('trim', explode(',', $channel->allowed_ips ?? ''));
        }
    }
}
