<?php

namespace Webkul\User\Http\Middleware;

use Illuminate\Support\Facades\Route;

class Bouncer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, \Closure $next, $guard = 'admin')
    {
        // Skip two-factor routes to prevent redirect loops
        if (
            $request->routeIs('admin.two_factor.*')
            || $request->routeIs('admin.session.destroy')
        ) {
            return $next($request);
        }

        if (! auth()->guard($guard)->check()) {
            return redirect()->route('admin.session.create');
        }

        /**
         * If user status is changed by admin. Then session should be
         * logged out.
         */
        if (! (bool) auth()->guard($guard)->user()->status) {
            auth()->guard($guard)->logout();

            return redirect()->route('admin.session.create');
        }

        /**
         * If somehow the user deleted all permissions, then it should be
         * auto logged out and need to contact the administrator again.
         */
        if ($this->isPermissionsEmpty()) {
            auth()->guard('admin')->logout();

            session()->flash('error', __('admin::app.error.403.message'));

            return redirect()->route('admin.session.create');
        }

        /**
         * If two-factor authentication is enabled for the user,
         * check if they have completed the verification process.
         */
        if ($this->isTwoFactorRequired($guard)) {
            return $this->handleTwoFactorRedirect($guard);
        }

        return $next($request);
    }

    /**
     * Check for user, if they have empty permissions or not except admin.
     *
     * @return bool
     */
    public function isPermissionsEmpty()
    {
        if (! $role = auth()->guard('admin')->user()->role) {
            abort(401, 'This action is unauthorized.');
        }

        if ($role->permission_type === 'all') {
            return false;
        }

        if (
            $role->permission_type !== 'all'
            && empty($role->permissions)
        ) {
            return true;
        }

        $this->checkIfAuthorized();

        return false;
    }

    /**
     * Check authorization.
     *
     * @return null
     */
    public function checkIfAuthorized()
    {
        $roles = acl()->getRoles();

        if (isset($roles[Route::currentRouteName()])) {
            bouncer()->allow($roles[Route::currentRouteName()]);
        }
    }

    /**
     * Check if two-factor authentication is required.
     */
    public function isTwoFactorRequired(string $guard): bool
    {
        $admin = auth()->guard($guard)->user();

        return $admin->two_factor_enabled && ! $this->hasPassedTwoFactor();
    }

    /**
     * Determine if two-factor authentication has been passed for this session.
     */
    protected function hasPassedTwoFactor(): bool
    {
        return (bool) session('two_factor_passed', false);
    }

    /**
     * Redirect to the correct two-factor flow.
     */
    public function handleTwoFactorRedirect(string $guard)
    {
        $admin = auth()->guard($guard)->user();

        if ($admin->two_factor_secret) {
            return redirect()->route('admin.two_factor.verify.form');
        }

        return redirect()->route('admin.two_factor.setup');
    }
}
