<?php

namespace Webkul\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Regex implements ValidationRule
{
    /**
     * Modifiers PCRE and the browser both understand.
     */
    public const SHARED_MODIFIERS = 'imsu';

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! self::isUsable($value)) {
            $fail('core::validation.regex')->translate();
        }
    }

    /**
     * Determine whether a pattern is one both PHP and the browser can compile.
     *
     * A pattern is handed to `preg_match` on the server and written into the form's rules
     * on the client as a literal, so anything PHP alone accepts — a `#` delimiter, a PCRE
     * only modifier — still takes the product form down with a syntax error.
     */
    public static function isUsable(mixed $pattern): bool
    {
        if (! is_string($pattern)) {
            return false;
        }

        if (! preg_match('#^/(.+)/([a-z]*)$#s', trim($pattern), $matches)) {
            return false;
        }

        if (preg_match('/[^'.self::SHARED_MODIFIERS.']/', $matches[2])) {
            return false;
        }

        return @preg_match($pattern, '') !== false;
    }
}
