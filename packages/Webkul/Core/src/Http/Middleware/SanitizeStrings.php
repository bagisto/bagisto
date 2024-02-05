<?php

namespace Webkul\Core\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\TransformsRequest;
use Webkul\Core\Traits\Sanitizer;

class SanitizeStrings extends TransformsRequest
{
    use Sanitizer;

    /**
     * All of the registered skip callbacks.
     *
     * @var array
     */
    protected static $skipCallbacks = [];

    /**
     * The names of the attributes that should not be sanitized.
     *
     * @var array<int, string>
     */
    protected $except = [];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        foreach (static::$skipCallbacks as $callback) {
            if ($callback($request)) {
                return $next($request);
            }
        }

        return parent::handle($request, $next);
    }

    /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        if (in_array($key, $this->except, true) || ! is_string($value)) {
            return $value;
        }

        return $this->sanitizeContent($value);
    }

    /**
     * Register a callback that instructs the middleware to be skipped.
     *
     * @return void
     */
    public static function skipWhen(Closure $callback)
    {
        static::$skipCallbacks[] = $callback;
    }
}
