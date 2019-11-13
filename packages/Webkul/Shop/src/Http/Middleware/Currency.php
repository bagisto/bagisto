<?php

namespace Webkul\Shop\Http\Middleware;

use Webkul\Core\Repositories\CurrencyRepository;
use Closure;

class Currency
{
    /**
     * @var CurrencyRepository
     */
    protected $currency;

    /**
     * @param \Webkul\Core\Repositories\CurrencyRepository $locale
     */
    public function __construct(CurrencyRepository $currency)
    {
        $this->currency = $currency;
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
        $currencyCode = request()->get('currency');

        if ($currency = $currencyCode) {
            if ($this->currency->findOneByField('code', $currency)) {
                session()->put('currency', $currency);
            }
        } else {
            if (! session()->get('currency')) {
                session()->put('currency', core()->getChannelBaseCurrencyCode());
            }
        }

        return $next($request);
    }
}