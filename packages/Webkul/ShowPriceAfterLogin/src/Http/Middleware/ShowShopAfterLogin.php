<?php

namespace Webkul\ShowPriceAfterLogin\Http\Middleware;

use Closure;

class ShowShopAfterLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $status =(boolean) core()->getConfigData('ShowPriceAfterLogin.settings.settings.hide-shop-before-login');
        $moduleEnabled =(boolean) core()->getConfigData('ShowPriceAfterLogin.settings.settings.enableordisable');

        if (!auth()->guard('customer')->check() && $moduleEnabled && ! request()->is('customer/*') && $status && ! request()->is('admin/*')) {
            return redirect()->route('customer.session.index');
        }
        return $next($request);
    }
}