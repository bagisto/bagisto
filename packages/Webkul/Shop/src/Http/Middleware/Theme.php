<?php

namespace Webkul\Shop\Http\Middleware;

use Closure;

class Theme
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
        $theme = app('themes');
        $channel = core()->getCurrentChannel();

        if($channel && $channelThemeCode = $channel->theme) {
            if($theme->exists($channelThemeCode)) {
                $theme->set($channelThemeCode);
            }
        }

        return $next($request);
    }
}