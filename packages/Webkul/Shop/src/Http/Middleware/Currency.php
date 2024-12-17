<?php

namespace Webkul\Shop\Http\Middleware;

use Closure;
use Webkul\Core\Repositories\CurrencyRepository;

class Currency
{
    /**
     * Create a middleware instance.
     *
     * @return void
     */
    public function __construct(protected CurrencyRepository $currencyRepository) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $currencies = core()->getCurrentChannel()->currencies->pluck('code')->toArray();
        $currencyCode = core()->getRequestedLocaleCode('currency', false);

        if (! $currencyCode || ! in_array($currencyCode, $currencies)) {
            $currencyCode = session()->get('currency');
        }

        if (! $currencyCode || ! in_array($currencyCode, $currencies)) {
            $currencyCode = core()->getCurrentChannel()->base_currency->code;
        }

        core()->setCurrentCurrency($currencyCode);
        session()->put('currency', $currencyCode);
        unset($request['currency']);

        return $next($request);
    }
}
