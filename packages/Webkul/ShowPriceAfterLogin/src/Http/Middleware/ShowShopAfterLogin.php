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
        $primaryServerName = config('app.url');

        $currentURL = $_SERVER['SERVER_NAME'];

        $params['domain'] = $currentURL;

        if (str_contains($primaryServerName, 'http://')) {
            $primaryServerNameWithoutProtocol = explode('http://', $primaryServerName)[1];
        } else if (str_contains($primaryServerName, 'https://')) {
            $primaryServerNameWithoutProtocol = explode('https://', $primaryServerName)[1];
        } else {
            $primaryServerNameWithoutProtocol = $primaryServerName;
        }

        if (str_contains($currentURL, 'http://')) {
            $currentServerNameWithoutProtocol = explode('http://', $currentURL)[1];
        } else if (str_contains($currentURL, 'https://')) {
            $currentServerNameWithoutProtocol = explode('https://', $currentURL)[1];
        } else {
            $currentServerNameWithoutProtocol = $currentURL;
        }

        if ($primaryServerNameWithoutProtocol == $currentServerNameWithoutProtocol) {
            return $next($request);
        }

        $status = core()->getConfigData('ShowPriceAfterLogin.settings.settings.hide-shop-before-login');
        $moduleEnabled = core()->getConfigData('ShowPriceAfterLogin.settings.settings.enableordisable');

        if ($status && $moduleEnabled) {
            if (! auth()->guard('customer')->check() && ! request()->is('customer/*') && ! request()->is('admin/*')) {
                return redirect()->route('customer.session.index');
            }
        }


        return $next($request);
    }
}