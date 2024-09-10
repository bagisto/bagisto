<?php

namespace Webkul\Shop\Http\Middleware;

use Closure;
use Webkul\Core\Repositories\LocaleRepository;

class Locale
{
    /**
     * Create a middleware instance.
     *
     * @return void
     */
    public function __construct(protected LocaleRepository $localeRepository) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($localeCode = core()->getRequestedLocaleCode('locale', false)) {
            if (in_array($localeCode, core()->getCurrentChannel()->locales->pluck('code')->toArray())) {
                app()->setLocale($localeCode);

                session()->put('locale', $localeCode);
            } else {
                $defaultLocaleCode = core()->getCurrentChannel()->default_locale->code;

                app()->setLocale($defaultLocaleCode);

                session()->put('locale', $defaultLocaleCode);
            }
        } elseif ($localeCode = session()->get('locale')) {
            app()->setLocale($localeCode);
        } else {
            app()->setLocale(core()->getDefaultLocaleCodeFromDefaultChannel());
        }

        unset($request['locale']);

        return $next($request);
    }
}
