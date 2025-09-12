<?php

namespace Webkul\TwoFactorAuth\Http\Middleware;

use Closure;

class TwoFactorMiddleware
{
    public function handle($request, Closure $next)
    {
        $admin = auth('admin')->user();

        $twoFactorEnabled = core()->getConfigData('general.two_factor_auth.settings.enabled');

        if ($request->routeIs('admin.twofactor.*')) {
            return $next($request);
        }

        if ($twoFactorEnabled && $admin && $admin->two_factor_enabled) {
            if (! session()->get('two_factor_passed')) {
                if ($admin->google2fa_secret) {
                    return redirect()->route('admin.twofactor.verify.form');
                }

                return redirect()->route('admin.twofactor.setup');
            }
        }

        return $next($request);
    }
}
