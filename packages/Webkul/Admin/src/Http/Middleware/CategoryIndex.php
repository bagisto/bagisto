<?php

namespace Webkul\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class CategoryIndex
{
    public function handle(Request $request, Closure $next)
    {
        $request->query->set('channel','all');
        return $next($request);
    }
}