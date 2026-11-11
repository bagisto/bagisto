<?php

namespace Webkul\User\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Bouncer
{
    /**
     * Routes every admin may reach whatever their role grants: their own record, and shared UI no permission owns.
     * A route neither listed nor mapped in `acl.php` is refused, so one added without an ACL entry fails closed.
     */
    const UNRESTRICTED_ROUTES = [
        'admin.account.edit',
        'admin.account.update',
        'admin.command_palette.index',
        'admin.datagrid.look_up',
        'admin.datagrid.saved_filters.destroy',
        'admin.datagrid.saved_filters.index',
        'admin.datagrid.saved_filters.store',
        'admin.datagrid.saved_filters.update',
        'admin.help.index',
        'admin.magic_ai.content',
        'admin.magic_ai.image',
        'admin.notification.index',
        'admin.notification.list',
        'admin.notification.mark_read',
        'admin.notification.mark_unread',
        'admin.notification.open',
        'admin.notification.read_all',
        'admin.settings.users.destroy',
        'admin.tinymce.upload',
        'admin.two_factor.disable',
        'admin.two_factor.enable',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, \Closure $next, $guard = 'admin')
    {
        if ($this->isTwoFactorRoute($request)) {
            return $next($request);
        }

        if (! auth()->guard($guard)->check()) {
            return redirect()->route('admin.session.create');
        }

        if (! (bool) auth()->guard($guard)->user()->status) {
            auth()->guard($guard)->logout();

            return redirect()->route('admin.session.create');
        }

        if ($this->isPermissionsEmpty()) {
            auth()->guard('admin')->logout();

            session()->flash('error', trans('admin::app.error.403.message'));

            return redirect()->route('admin.session.create');
        }

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
        $routeName = Route::currentRouteName();

        if (in_array($routeName, self::UNRESTRICTED_ROUTES)) {
            return;
        }

        $roles = acl()->getRoles();

        if (! isset($roles[$routeName])) {
            abort(401, 'This action is unauthorized.');
        }

        bouncer()->allow($roles[$routeName]);
    }

    /**
     * Check if two-factor authentication is required.
     */
    public function isTwoFactorRequired(string $guard): bool
    {
        $admin = auth()->guard($guard)->user();

        return $admin->two_factor_enabled
            && ! $this->hasPassedTwoFactor();
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

    /**
     * Whether the route sets up or completes two-factor sign in, or signs out, which skip the check so it cannot loop.
     * Disabling two-factor is not one, so a session yet to pass it cannot switch it off to get round it.
     */
    protected function isTwoFactorRoute(Request $request): bool
    {
        return $request->routeIs('admin.two_factor.setup')
            || $request->routeIs('admin.two_factor.verify.form')
            || $request->routeIs('admin.two_factor.verify.store')
            || $request->routeIs('admin.session.destroy');
    }

    /**
     * Determine if two-factor authentication has been passed for this session.
     */
    protected function hasPassedTwoFactor(): bool
    {
        $passedFor = session('two_factor_passed_for');

        return $passedFor
            && (int) $passedFor === (int) auth()->guard('admin')->id();
    }
}
