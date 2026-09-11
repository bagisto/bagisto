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
        if ($this->isAlreadyInstalled()) {
            if ($this->isInstallerRequest($request)) {
                if (! $request->ajax()) {
                    return redirect()->route('shop.home.index');
                }

                return response()->json([
                    'message' => trans('installer::app.installer.middleware.already-installed'),
                ], 403);
            }
        } elseif (! $this->isInstallerRequest($request)) {
            return redirect()->route('installer.index');
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

    /**
     * Whether the request targets the installer.
     *
     * The comparison is made on the decoded path. The router resolves a percent-encoded path such
     * as `/%69nstall` to the installer, so matching the raw path here would let it slip past the
     * guard on an installed site.
     */
    protected function isInstallerRequest(Request $request): bool
    {
        $path = trim($request->decodedPath(), '/');

        return $path === 'install'
            || Str::startsWith($path, 'install/');
    }
}
