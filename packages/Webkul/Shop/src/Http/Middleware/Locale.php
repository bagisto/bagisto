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
            if ($this->localeRepository->findOneByField('code', $localeCode)) {
                app()->setLocale($localeCode);

                session()->put('locale', $localeCode);
            }
        } else {
            if ($localeCode = session()->get('locale')) {
                app()->setLocale($localeCode);
            } else {
                app()->setLocale(core()->getDefaultLocaleCodeFromDefaultChannel());
            }
        }

        unset($request['locale']);

        return $next($request);
    }
}
