<?php

namespace Webkul\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Regex implements ValidationRule
{
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
     * on the client, so an undelimited or malformed one takes the product form down
     * rather than merely failing to match.
     */
    public static function isUsable(mixed $pattern): bool
    {
        if (
            ! is_string($pattern)
            || trim($pattern) === ''
        ) {
            return false;
        }

        return @preg_match($pattern, '') !== false;
    }
}
