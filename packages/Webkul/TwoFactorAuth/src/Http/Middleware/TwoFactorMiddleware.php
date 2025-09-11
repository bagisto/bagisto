<?php

namespace Webkul\TwoFactorAuth\Http\Middleware;

use Closure;

class TwoFactorMiddleware
{
    public function handle($request, Closure $next)
    {
        $admin = auth('admin')->user();

        $twoFactorEnabled = core()->getConfigData('general.two_factor_auth.settings.enabled');

        if ($request->routeIs('admin.twofactor.setup') ||
            $request->routeIs('admin.twofactor.enable') ||
            $request->routeIs('admin.twofactor.verify.form') ||
            $request->routeIs('admin.twofactor.verify')) {
            return $next($request);
        }

        if ($twoFactorEnabled && $admin && $admin->two_factor_enabled) {
            if (! session()->get('two_factor_passed')) {
                if ($admin->google2fa_secret) {
                    logger()->warning('Redirecting to 2FA verify page', ['admin_id' => $admin->id]);
                    return redirect()->route('admin.twofactor.verify.form');
                }
                logger()->warning('Redirecting to 2FA setup page', ['admin_id' => $admin->id]);
                return redirect()->route('admin.twofactor.setup');
            }
        }

        logger()->info('2FA check passed → continuing request', ['admin_id' => $admin?->id]);
        return $next($request);
    }

}
