<?php

namespace Webkul\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SecureHeaders
{
    /**
     * Headers to remove to avoid disclosing server/framework details.
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
     * @param  Request  $request
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
     * @param  Response  $response
     * @return void
     */
    private function setHeaders($response)
    {
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(self)');

        $csp = env('CONTENT_SECURITY_POLICY', '');

        if (! empty($csp)) {
            $response->headers->set('Content-Security-Policy', $csp);
        }
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
