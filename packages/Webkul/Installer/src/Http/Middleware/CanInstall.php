<?php

namespace Webkul\Installer\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CanInstall
{
    public function handle(Request $request, Closure $next)
    {
        if (Str::contains($request->getPathInfo(), '/install')) {
            if ($this->isAlreadyInstalled()) {
                return redirect()->route('shop.home.index');
            }
        } else {
            if (! $this->isAlreadyInstalled()) {
                return redirect()->route('installer.index');
            }
        }

        return $next($request);
    }

    /**
     * If application is already installed.
     *
     * @return bool
     */
    public function isAlreadyInstalled()
    {
        return file_exists(storage_path('installed'));
    }
}