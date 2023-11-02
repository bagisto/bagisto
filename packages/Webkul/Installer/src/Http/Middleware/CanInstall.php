<?php

namespace Webkul\Installer\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Webkul\Installer\Helpers\DatabaseManager;

class CanInstall
{
    /**
     * Handles Requests for Installer middleware.
     *
     * @return void
     */
    public function handle(Request $request, Closure $next)
    {
        if (Str::contains($request->getPathInfo(), '/install')) {
            if ($this->isAlreadyInstalled() && ! $request->ajax()) {
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
     * Application Already Installed.
     *
     * @return bool
     */
    public function isAlreadyInstalled()
    {
        if (file_exists(storage_path('installed'))) {
            return true;
        }

        if (app(DatabaseManager::class)->isInstalled()) {
            touch(storage_path('installed'));

            Event::dispatch('bagisto.installed');

            return true;
        }

        return false;
    }
}
