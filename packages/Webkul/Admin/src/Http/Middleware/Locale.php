<?php

namespace Webkul\Admin\Http\Middleware;

use Closure;
use Webkul\Core\Repositories\LocaleRepository;

class Locale
{
    /**
     * Locale repository.
     *
     * @var LocaleRepository
     */
    protected $locale;

    /**
     * Create a new middleware instance.
     *
     * @param  \Webkul\Core\Repositories\LocaleRepository $locale
     */
    public function __construct(LocaleRepository $locale)
    {
        $this->locale = $locale;
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
            if ($this->locale->findOneByField('code', $locale)) {
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
