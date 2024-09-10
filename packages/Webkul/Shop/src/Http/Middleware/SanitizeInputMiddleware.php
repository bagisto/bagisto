<?php

namespace Webkul\Shop\Http\Middleware;

use Closure;

class SanitizeInputMiddleware
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
        // Sanitize all request data
        $sanitized = $this->sanitizeArray($request->all());

        // Replace the request's input with the sanitized data
        $request->merge($sanitized);

        return $next($request);
    }

    /**
     * Sanitizing the input recursively.
     */
    private function sanitizeArray(mixed $input): mixed
    {
        if (is_array($input)) {
            return array_map([$this, 'sanitizeArray'], $input);
        }

        return htmlspecialchars(
            str_replace(['(', ')'], ['&#x28;', '&#x29;'], preg_replace('/\{\{(.*?)\}\}/', '$1', (string) $input)),
            ENT_QUOTES, 'UTF-8'
        );
    }
}
