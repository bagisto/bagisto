<?php

namespace Webkul\Core\Http\Middleware;

use Closure;

class SecureHeaders
{
    /**
     * Unwanted header list.
     *
     * @var array
     */
    private $unwantedHeaderList = [
        'X-Powered-By',
        'Server',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->removeUnwantedHeaders();

        $response = $next($request);

        $this->setHeaders($response);

        return $response;
    }

    /**
     * Set headers.
     *
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    private function setHeaders($response)
    {
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
    }

    /**
     * Remove unwanted headers.
     *
     * @return void
     */
    private function removeUnwantedHeaders()
    {
        if (headers_sent()) {
            return;
        }

        foreach ($this->unwantedHeaderList as $header) {
            header_remove($header);
        }
    }
}
