<?php

namespace Webkul\User\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Bouncer
{
    /**
     * Routes every signed-in admin may reach whatever their role grants. They either
     * act on the admin's own record, or back shared UI - notifications, the datagrid
     * chrome, the editor's uploader - that no single permission owns. Anything not
     * listed here and not mapped in `acl.php` is refused, so a route added without an
     * ACL entry fails closed instead of being silently open to every role.
     */
    const UNRESTRICTED_ROUTES = [
        'admin.account.edit',
        'admin.account.update',
        'admin.two_factor.enable',
        'admin.two_factor.disable',
        'admin.settings.users.destroy',
        'admin.help.index',
        'admin.notification.index',
        'admin.notification.get_notification',
        'admin.notification.read_all',
        'admin.notification.viewed_notification',
        'admin.datagrid.look_up',
        'admin.datagrid.saved_filters.index',
        'admin.datagrid.saved_filters.store',
        'admin.datagrid.saved_filters.update',
        'admin.datagrid.saved_filters.destroy',
        'admin.magic_ai.content',
        'admin.magic_ai.image',
        'admin.tinymce.upload',
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
        /**
         * Only the routes required to set up or complete two-factor
         * authentication may bypass the verification check below (otherwise
         * the redirect to the verification/setup screen would loop). Every
         * other two-factor action - in particular disabling 2FA - must stay
         * behind the verification check, so that a session which has logged in
         * with the password but has not passed two-factor verification cannot
         * use it to switch two-factor authentication off and bypass it.
         */
        if (
            $request->routeIs('admin.two_factor.setup')
            || $request->routeIs('admin.two_factor.verify.form')
            || $request->routeIs('admin.two_factor.verify.store')
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

            session()->flash('error', trans('admin::app.error.403.message'));

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
