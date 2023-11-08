<?php

namespace Webkul\Installer\Http\Middleware;

use Closure;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($localeCode = $request->query('locale')) {
            app()->setLocale($localeCode);

            session()->put('installer_locale', $localeCode);
        } else {
            app()->setLocale(session()->get('installer_locale') ?? config('app.locale'));
        }

        return $next($request);
    }
}
