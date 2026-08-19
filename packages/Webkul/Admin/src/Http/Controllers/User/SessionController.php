<?php

namespace Webkul\Admin\Http\Controllers\User;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;

class SessionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        if (auth()->guard('admin')->check()) {
            return redirect()->route('admin.dashboard.index');
        }

        if (strpos(url()->previous(), 'admin') !== false) {
            $intendedUrl = url()->previous();
        } else {
            $intendedUrl = route('admin.dashboard.index');
        }

        session()->put('url.intended', $intendedUrl);

        return view('admin::users.sessions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $this->validate(request(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = request('remember');

        if (! auth()->guard('admin')->attempt(request(['email', 'password']), $remember)) {
            session()->flash('error', trans('admin::app.settings.users.login-error'));

            return redirect()->back();
        }

        if (! auth()->guard('admin')->user()->status) {
            session()->flash('warning', trans('admin::app.settings.users.activate-warning'));

            auth()->guard('admin')->logout();

            return redirect()->route('admin.session.create');
        }

        if (! bouncer()->hasPermission('dashboard')) {
            return $this->redirectToFirstAccessibleRoute();
        }

        return redirect()->intended(route('admin.dashboard.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy()
    {
        auth()->guard('admin')->logout();

        session()->forget('two_factor_passed');

        return redirect()->route('admin.session.create');
    }

    /**
     * Redirect to the first accessible route based on user permissions.
     *
     * @return RedirectResponse
     */
    private function redirectToFirstAccessibleRoute()
    {
        $allPermissions = collect(config('acl'));
        $userPermissions = auth()->guard('admin')->user()->role->permissions;

        foreach ($userPermissions as $permission) {
            if (! bouncer()->hasPermission($permission)) {
                continue;
            }

            $permissionDetails = $allPermissions->firstWhere('key', $permission);

            if (! $permissionDetails) {
                continue;
            }

            if ($route = $this->navigableRoute($permissionDetails)) {
                return redirect()->route($route);
            }

            $childPermission = $this->findFirstAccessibleChildPermission($allPermissions, $permission);

            if (
                $childPermission
                && $route = $this->navigableRoute($childPermission)
            ) {
                return redirect()->route($route);
            }
        }

        return redirect()->intended(route('admin.dashboard.index'));
    }

    /**
     * The route a permission can land an admin on, or null when it has none.
     *
     * A permission commonly guards several routes, and most of them are nowhere to send a
     * browser: the ones that write answer no GET, and others want an id that signing in
     * has no way of knowing.
     */
    private function navigableRoute($permission): ?string
    {
        foreach ((array) ($permission['route'] ?? []) as $name) {
            $route = Route::getRoutes()->getByName($name);

            if (
                ! $route
                || ! in_array('GET', $route->methods())
            ) {
                continue;
            }

            if (preg_match('/\{[^}?]+\}/', $route->uri())) {
                continue;
            }

            return $name;
        }

        return null;
    }

    /**
     * Recursively find the first accessible child permission.
     *
     * @param  Collection  $allPermissions
     * @param  string  $parentKey
     * @return array|null
     */
    private function findFirstAccessibleChildPermission($allPermissions, $parentKey)
    {
        $children = $allPermissions->filter(function ($item) use ($parentKey) {
            return str_starts_with($item['key'], $parentKey.'.')
                && substr_count($item['key'], '.') === substr_count($parentKey, '.') + 1
                && bouncer()->hasPermission($item['key']);
        })->values();

        if ($children->isEmpty()) {
            return null;
        }

        foreach ($children as $child) {
            if ($this->hasAllRequiredPermissionsForRoute($allPermissions, $child['route'])) {
                return $child;
            }

            $descendant = $this->findFirstAccessibleChildPermission($allPermissions, $child['key']);

            if ($descendant) {
                return $descendant;
            }
        }

        return null;
    }

    /**
     * Check if user has all required permissions for a given route.
     *
     * @param  Collection  $allPermissions
     * @param  string  $route
     * @return bool
     */
    private function hasAllRequiredPermissionsForRoute($allPermissions, $route)
    {
        $requiredPermissions = $allPermissions->where('route', $route);

        foreach ($requiredPermissions as $permission) {
            if (! bouncer()->hasPermission($permission['key'])) {
                return false;
            }
        }

        return true;
    }
}
