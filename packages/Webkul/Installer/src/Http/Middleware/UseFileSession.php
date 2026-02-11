<?php

namespace Webkul\Installer\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class UseFileSession
{
    /**
     * Handle an incoming request.
     *
     * Forces file-based sessions during installation to avoid database dependency.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Force file-based sessions before `web` middleware group runs.
        Config::set('session.driver', 'file');

        return $next($request);
    }
}
