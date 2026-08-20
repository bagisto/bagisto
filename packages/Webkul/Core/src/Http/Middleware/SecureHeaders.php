<?php

namespace Webkul\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SecureHeaders
{
    /**
     * Request attribute a route sets to say its response may be framed by this site.
     */
    public const FRAMABLE = 'framable';

    /**
     * Unwanted header list.
     *
     * @var array
     */
    private $unwantedHeaderList = [];

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->removeUnwantedHeaders();

        $response = $next($request);

        $this->setHeaders($response, $request);

        return $response;
    }

    /**
     * Set headers.
     *
     * @param  Response  $response
     * @param  Request  $request
     * @return void
     */
    private function setHeaders($response, $request = null)
    {
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->headers->set('X-Built-With', 'Bagisto');

        if ($request?->attributes->get(self::FRAMABLE)) {
            $response->headers->remove('X-Frame-Options');

            $response->headers->set('Content-Security-Policy', "frame-ancestors 'self'");

            return;
        }

        $response->headers->set('X-Frame-Options', 'DENY');
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
