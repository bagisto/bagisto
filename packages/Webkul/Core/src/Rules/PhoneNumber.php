<?php

namespace Webkul\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /**
         * This regular expression allows phone numbers with the following conditions:
         * - The phone number can start with an optional "+" sign.
         * - It must contain at least one digit, and nothing but digits grouped by
         *   spaces, dots, dashes and brackets.
         *
         * This validation is sufficient for global-level phone number validation. If
         * someone wants to customize it, they can override this rule.
         */
        if (! preg_match('/^\+?[\s(]*\d[\d\s.\-()]*$/', $value)) {
            $fail('core::validation.phone-number')->translate();
        }
    }
}
