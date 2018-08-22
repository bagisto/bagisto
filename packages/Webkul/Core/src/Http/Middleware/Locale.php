<?php

namespace Webkul\Core\Http\Middleware;

use Webkul\Core\Models\Locale as LocaleModel;
use Webkul\Core\Repositories\LocaleRepository;
use Closure;

class Locale
{
    /**
     * @var \Webkul\Core\Repositories\LocaleRepository
     */
    protected $locale;

    /**
     * @param \Webkul\Core\Repositories\LocaleRepository $locale
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
        if($locale = $request->get('locale')) {
            if($this->locale->findOneByField('code', $locale)) {
                // app()->setLocale($locale);
            }
        }

        return $next($request);
    }
}