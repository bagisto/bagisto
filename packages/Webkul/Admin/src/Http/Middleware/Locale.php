<?php

namespace Webkul\Admin\Http\Middleware;

use Closure;
use Webkul\Core\Repositories\LocaleRepository;

class Locale
{
    /**
     * Create a new middleware instance.
     *
     * @param  \Webkul\Core\Repositories\LocaleRepository $localeRepository
     */
    public function __construct(protected LocaleRepository $localeRepository)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = request()->get('admin_locale');

        if ($locale) {
            if ($this->localeRepository->findOneByField('code', $locale)) {
                app()->setLocale($locale);

                session()->put('admin_locale', $locale);
            }
        } else {
            if ($locale = session()->get('admin_locale')) {
                app()->setLocale($locale);
            } else {
                app()->setLocale(app()->getLocale());
            }
        }

        unset($request['admin_locale']);

        return $next($request);
    }
}
