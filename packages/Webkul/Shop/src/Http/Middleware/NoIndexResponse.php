<?php

namespace Webkul\Shop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoIndexResponse
{
    /**
     * Ask search engines not to index the response, which stays crawlable for rendering the pages
     * that print its url into their markup.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Robots-Tag', 'noindex');

        return $response;
    }
}
