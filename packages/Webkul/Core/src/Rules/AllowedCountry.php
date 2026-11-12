<?php

namespace Webkul\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedCountry implements ValidationRule
{
    /**
     * Run the validation rule, passing every country while the storefront is not limited to some.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $allowed = core()->allowedCountryCodes();

        if (
            ! $allowed
            || blank($value)
        ) {
            return;
        }

        if (
            ! is_string($value)
            || ! in_array(mb_strtoupper(trim($value)), array_map('mb_strtoupper', $allowed), true)
        ) {
            $fail('validation.in')->translate();
        }
    }
}
