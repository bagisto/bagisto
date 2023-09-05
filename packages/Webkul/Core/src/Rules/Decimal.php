<?php

namespace Webkul\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Decimal implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^\d*(\.\d{1,4})?$/', $value)) {
            $fail('core::validation.decimal')->translate();
        }
    }
}
